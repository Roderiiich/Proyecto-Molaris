<?php

namespace App\Http\Controllers;

use App\Models\Presupuesto;
use App\Models\PresupuestoDetalle;
use App\Models\Paciente;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresupuestoController extends Controller
{
    public function index(Request $request)
    {
        $presupuestos = Presupuesto::with([
            'paciente',
            'dentista.usuario'
        ])
        ->when($request->filled('buscar'), function ($query) use ($request) {
            $buscar = trim($request->buscar);

            $query->whereHas('paciente', function ($q) use ($buscar) {
                $q->where('nombre', 'ILIKE', "%{$buscar}%")
                ->orWhere('rut', 'ILIKE', "%{$buscar}%");
            });
        })
        ->when($request->filled('estado'), function ($query) use ($request) {
            $query->where('estado', $request->estado);
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

        return view('presupuestos.index', compact('presupuestos'));
    }

    public function create()
    {
        // Pacientes: la tabla utiliza "nombre"
        $pacientes = Paciente::select(
            'id',
            'nombre',
            'rut'
        )
        ->orderBy('nombre')
        ->get();

        // Doctores: el nombre está en la tabla users,
        // relacionada mediante usuario_id
        $dentistas = Doctor::with('usuario')
            ->get()
            ->sortBy(function ($doctor) {
                return $doctor->usuario->name ?? '';
            });

        return view(
            'presupuestos.create',
            compact('pacientes', 'dentistas')
        );
    }

    public function store(Request $request)
{
    $request->validate([
        'paciente_id' => 'required|exists:pacientes,id',

        'dentista_id' => 'required|exists:doctores,id',

        'items' => 'required|array|min:1',

        'items.*.nombre_tratamiento' => [
            'required',
            'string',
            'max:255'
        ],

        'items.*.especialidad' => [
            'nullable',
            'string',
            'max:100'
        ],

        'items.*.cantidad' => [
            'required',
            'integer',
            'min:1'
        ],

        'items.*.precio_unitario' => [
            'required',
            'numeric',
            'min:0'
        ],

        'observaciones' => [
            'nullable',
            'string'
        ],
    ]);

    DB::transaction(function () use ($request) {

        $montoTotal = 0;

        // Calcular el monto total
        foreach ($request->items as $item) {
            $montoTotal +=
                $item['cantidad'] *
                $item['precio_unitario'];
        }

        // Crear cabecera del presupuesto
        $presupuesto = Presupuesto::create([
            'paciente_id' => $request->paciente_id,
            'dentista_id' => $request->dentista_id,
            'monto_total' => $montoTotal,
            'monto_pagado' => 0,
            'estado' => 'borrador',
            'observaciones' => $request->observaciones,
        ]);

        // Crear los detalles del presupuesto
        foreach ($request->items as $item) {

            $subtotal =
                $item['cantidad'] *
                $item['precio_unitario'];

            PresupuestoDetalle::create([
                'presupuesto_id' => $presupuesto->id,

                'tratamiento_nombre' =>
                    $item['nombre_tratamiento'],

                'cantidad' =>
                    $item['cantidad'],

                'precio_unitario' =>
                    $item['precio_unitario'],

                'subtotal' =>
                    $subtotal,
            ]);
        }
    });

    return redirect()
        ->route('financiero.presupuestos.index')
        ->with(
            'success',
            'Presupuesto creado con éxito.'
        );
}

    public function show(Presupuesto $presupuesto)
    {
        $presupuesto->load([
            'paciente',
            'dentista.usuario',
            'detalles',
            'pagos.usuario'
        ]);

        return view(
            'presupuestos.show',
            compact('presupuesto')
        );
    }

    public function cambiarEstado(
        Request $request,
        Presupuesto $presupuesto
    ) {
        // Validación
        $request->validate([
            'estado' => [
                'required',
                'string',
                'in:borrador,pendiente,completado,anulado'
            ],

            'motivo_anulacion' => [
                'nullable',
                'required_if:estado,anulado',
                'string',
                'max:255'
            ],
        ], [
            'estado.in' =>
                'El estado seleccionado no es válido.',

            'motivo_anulacion.required_if' =>
                'Debe indicar el motivo de la anulación.',
        ]);

        $nuevoEstado = $request->input('estado');
        $estadoActual = $presupuesto->estado;

        // Si el estado no cambia
        if ($estadoActual === $nuevoEstado) {

            return redirect()
                ->back()
                ->with(
                    'info',
                    'El presupuesto ya se encuentra en estado ' .
                    ucfirst($nuevoEstado) .
                    '.'
                );
        }

        // Un presupuesto anulado no puede modificarse
        if ($estadoActual === 'anulado') {

            return redirect()
                ->back()
                ->withErrors([
                    'estado' =>
                        'No es posible cambiar el estado de un presupuesto que ya ha sido anulado.'
                ]);
        }

        // No permitir completar si existe saldo pendiente
        if (
            $nuevoEstado === 'completado' &&
            $presupuesto->saldo_pendiente > 0
        ) {

            return redirect()
                ->back()
                ->withErrors([
                    'estado' =>
                        'No se puede marcar el presupuesto como completado porque aún mantiene un saldo pendiente de $' .
                        number_format(
                            $presupuesto->saldo_pendiente,
                            0,
                            ',',
                            '.'
                        ) .
                        '.'
                ]);
        }

        // No permitir volver a borrador si existen pagos
        if (
            $nuevoEstado === 'borrador' &&
            $presupuesto->monto_pagado > 0
        ) {

            return redirect()
                ->back()
                ->withErrors([
                    'estado' =>
                        'No se puede cambiar a borrador un presupuesto que ya registra abonos o pagos.'
                ]);
        }

        try {

            DB::transaction(function () use (
                $presupuesto,
                $nuevoEstado,
                $request
            ) {

                $datosActualizacion = [
                    'estado' => $nuevoEstado,
                ];

                // Si se anula, guardar motivo y fecha
                if ($nuevoEstado === 'anulado') {

                    $datosActualizacion[
                        'motivo_anulacion'
                    ] = $request->input(
                        'motivo_anulacion'
                    );

                    $datosActualizacion[
                        'fecha_anulacion'
                    ] = now();
                }

                $presupuesto->update(
                    $datosActualizacion
                );
            });

            return redirect()
                ->back()
                ->with(
                    'success',
                    'El estado del presupuesto #' .
                    str_pad(
                        $presupuesto->id,
                        6,
                        '0',
                        STR_PAD_LEFT
                    ) .
                    ' ha sido actualizado a "' .
                    ucfirst($nuevoEstado) .
                    '".'
                );

        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withErrors([
                    'error' =>
                        'Ocurrió un error al actualizar el estado del presupuesto: ' .
                        $e->getMessage()
                ]);
        }
    }
}