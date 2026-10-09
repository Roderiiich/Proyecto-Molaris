<?php

namespace App\Http\Controllers\Financiero;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Liquidacion;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ComisionController extends Controller
{
    /**
     * Muestra la vista de liquidaciones con el historial.
     */
    public function liquidaciones()
    {
        $dentistas = Usuario::whereHas('rol', function ($q) {
            $q->where('nombre', 'Dentista');
        })->get();

        $liquidaciones = Liquidacion::with('dentista')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('financiero.comisiones.liquidaciones', compact('dentistas', 'liquidaciones'));
    }

    /**
     * Endpoint API/AJAX para calcular la recaudación entre fechas.
     */
    public function calcularRecaudacion(Request $request)
    {
        $hoy = Carbon::now()->format('Y-m-d');

        $request->validate([
            'dentista_id'    => 'required|exists:usuarios,id',
            'periodo_inicio' => 'required|date|before_or_equal:' . $hoy,
            'periodo_fin'    => 'required|date|after_or_equal:periodo_inicio|before_or_equal:' . $hoy,
        ], [
            'periodo_fin.before_or_equal' => 'La fecha fin no puede ser posterior al día de hoy.',
            'periodo_inicio.before_or_equal' => 'La fecha de inicio no puede ser futura.',
        ]);

        $dentistaId = $request->input('dentista_id');
        $inicio = $request->input('periodo_inicio');
        $fin = $request->input('periodo_fin');

        // Lógica de cálculo de recaudación de pagos...
        // Ejemplo ficticio o llamada a tu Repository/Service:
        $totalRecaudado = \DB::table('pagos')
            ->join('presupuestos', 'pagos.presupuesto_id', '=', 'presupuestos.id')
            ->where('presupuestos.dentista_id', $dentistaId)
            ->whereBetween('pagos.fecha_pago', [$inicio, $fin])
            ->sum('pagos.monto') ?? 0;

        $porcentaje = 45; // Tasa por defecto o traída de configuración
        $totalComision = $totalRecaudado * ($porcentaje / 100);

        return response()->json([
            'total_recaudado' => $totalRecaudado,
            'porcentaje'      => $porcentaje,
            'total_comision'   => $totalComision,
        ]);
    }

    /**
     * Guarda la nueva liquidación generada.
     */
    public function store(Request $request)
    {
        $hoy = Carbon::now()->format('Y-m-d');

        // Validación estricta que impide guardar liquidaciones a futuro
        $validated = $request->validate([
            'dentista_id'    => 'required|exists:usuarios,id',
            'periodo_inicio' => 'required|date|before_or_equal:' . $hoy,
            'periodo_fin'    => 'required|date|after_or_equal:periodo_inicio|before_or_equal:' . $hoy,
            'total_recaudado' => 'required|numeric|min:0',
            'total_comision'  => 'required|numeric|min:0',
        ], [
            'periodo_fin.before_or_equal' => 'No es posible registrar liquidaciones con fecha fin futura.',
            'periodo_inicio.before_or_equal' => 'La fecha de inicio no puede ser posterior a hoy.',
            'periodo_fin.after_or_equal' => 'La fecha de término debe ser posterior o igual a la de inicio.',
        ]);

        // Crear registro en la BD
        Liquidacion::create([
            'dentista_id'    => $validated['dentista_id'],
            'periodo_inicio' => $validated['periodo_inicio'],
            'periodo_fin'    => $validated['periodo_fin'],
            'total_recaudado'=> $validated['total_recaudado'],
            'total_comision' => $validated['total_comision'],
            'estado'          => 'pendiente',
        ]);

        return redirect()
            ->route('financiero.comisiones.liquidaciones.index')
            ->with('success', 'Liquidación emitida y registrada exitosamente.');
    }
}