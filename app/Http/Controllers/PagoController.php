<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Presupuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PagoController extends Controller
{
    /**
     * Procesa y registra un abono/pago para un presupuesto.
     */
   public function store(Request $request)
    {
        // 1. Validación de datos recibidos
        $validated = $request->validate([
            'presupuesto_id'    => 'required|exists:presupuestos,id',
            'monto'             => 'required|numeric|min:1',
            'medio_pago'        => 'required|in:efectivo,transferencia,pos_transbank',
            'numero_referencia' => 'nullable|string|max:100',
        ]);

        try {
            // 2. Transacción de base de datos para asegurar consistencia
            $pago = \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
                // Obtener el presupuesto con bloqueo pesimista (lockForUpdate) para evitar condiciones de carrera
                $presupuesto = \App\Models\Presupuesto::lockForUpdate()->findOrFail($validated['presupuesto_id']);

                // Validar que el monto abonado no supere el saldo pendiente
                if ($validated['monto'] > $presupuesto->saldo_pendiente) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'monto' => 'El monto ingresado ($' . number_format($validated['monto'], 0, ',', '.') . ') supera el saldo pendiente ($' . number_format($presupuesto->saldo_pendiente, 0, ',', '.') . ').'
                    ]);
                }

                // Crear el registro del pago
                $nuevoPago = \App\Models\Pago::create([
                    'presupuesto_id'    => $presupuesto->id,
                    'usuario_id'        => auth()->id(),
                    'monto'             => $validated['monto'],
                    'medio_pago'        => $validated['medio_pago'],
                    'numero_referencia' => $validated['numero_referencia'] ?? null,
                    'fecha_pago'        => now(),
                ]);

                // Actualizar saldos en el presupuesto
                $presupuesto->monto_pagado += $validated['monto'];
                $presupuesto->saldo_pendiente = max(0, $presupuesto->monto_total - $presupuesto->monto_pagado);

                // Si el saldo pendiente llega a cero, actualizar el estado a completado automáticamente
                if ($presupuesto->saldo_pendiente <= 0 && $presupuesto->estado === 'pendiente') {
                    $presupuesto->estado = 'completado';
                }

                $presupuesto->save();

                return $nuevoPago;
            });

            return redirect()->route('financiero.pagos.comprobante', $pago->id)
                ->with('success', 'Abono registrado correctamente.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ocurrió un error al procesar el pago: ' . $e->getMessage()])->withInput();
        }
    }
    public function cierreCaja(Request $request)
    {
        $hoy = now()->format('Y-m-d');

        // Validar que la fecha no sea superior al día de hoy
        $request->validate([
            'fecha' => "nullable|date|before_or_equal:{$hoy}",
        ], [
            'fecha.before_or_equal' => 'No es posible consultar cierres de caja de fechas futuras.',
        ]);

        // Fecha seleccionada para el cierre (por defecto la actual)
        $fecha = $request->input('fecha', $hoy);

        // Bloqueo de seguridad: Si intentan forzar una fecha futura, reajusta a hoy
        if ($fecha > $hoy) {
            $fecha = $hoy;
        }

        // Consultar los pagos correspondientes al día seleccionado
        $pagosDelDia = Pago::with(['presupuesto.paciente', 'usuario'])
            ->whereDate('fecha_pago', $fecha)
            ->orderBy('created_at', 'desc')
            ->get();

        // Totales desglosados por medio de pago
        $totalEfectivo     = $pagosDelDia->where('medio_pago', 'efectivo')->sum('monto');
        $totalTransferencia = $pagosDelDia->where('medio_pago', 'transferencia')->sum('monto');
        $totalTransbank    = $pagosDelDia->where('medio_pago', 'pos_transbank')->sum('monto');
        $totalGeneral      = $pagosDelDia->sum('monto');

        // Métricas rápidas
        $resumen = [
            'fecha'                  => $fecha,
            'total_efectivo'         => $totalEfectivo,
            'total_transferencia'    => $totalTransferencia,
            'total_transbank'        => $totalTransbank,
            'total_general'          => $totalGeneral,
            'cantidad_transacciones' => $pagosDelDia->count(),
        ];

        return view('pagos.cierre', compact('pagosDelDia', 'resumen', 'fecha'));
    }

    /**
     * Genera la vista imprimible del comprobante de abono / recibo de pago.
     *
     * @param  \App\Models\Pago  $pago
     * @return \Illuminate\View\View
     */
    public function generarComprobante(Pago $pago)
    {
        // Cargar las relaciones necesarias para el comprobante
        $pago->load(['presupuesto.paciente', 'presupuesto.dentista', 'usuario']);

        return view('pagos.comprobante', compact('pago'));
    }
}