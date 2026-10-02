<?php

namespace App\Http\Controllers\Financiero;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Liquidacion;
use App\Models\Pago;
use App\Models\TasaComision;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ComisionController extends Controller
{
    /**
     * Muestra la vista de configuración de tasas de comisión.
     */
    public function index()
    {
        $dentistas = Doctor::with('usuario')->get(); 
        $tasas = TasaComision::all()->groupBy('dentista_id');

        $especialidadesDisponibles = [
            'Odontología General',
            'Endodoncia',
            'Ortodoncia',
            'Periodoncia',
            'Cirugía Bucal',
            'Implantología',
            'Rehabilitación Oral'
        ];

        return view('financiero.comisiones.index', compact(
            'dentistas', 
            'tasas', 
            'especialidadesDisponibles'
        ));
    }

    /**
     * Guarda o actualiza la tasa de comisión para un odontólogo y especialidad.
     */
    public function storeTasa(Request $request)
    {
        $request->validate([
            'dentista_id'  => 'required',
            'especialidad' => 'required|string|max:255',
            'porcentaje'   => 'required|numeric|min:0|max:100',
        ]);

        TasaComision::updateOrCreate(
            [
                'dentista_id'  => $request->dentista_id,
                'especialidad' => $request->especialidad,
            ],
            [
                'porcentaje'   => $request->porcentaje,
            ]
        );

        return redirect()->back()->with('success', 'Tasa asignada con éxito.');
    }

    /**
     * Actualiza una tasa de comisión existente.
     */
    public function updateTasa(Request $request, $id)
    {
        $request->validate([
            'especialidad' => 'required|string|max:255',
            'porcentaje'   => 'required|numeric|min:0|max:100',
        ]);

        $tasa = TasaComision::findOrFail($id);
        $tasa->update([
            'especialidad' => $request->especialidad,
            'porcentaje'   => $request->porcentaje,
        ]);

        return redirect()->back()->with('success', 'Tasa de comisión actualizada correctamente.');
    }

    /**
     * Elimina una tasa de comisión configurada.
     */
    public function destroyTasa($id)
    {
        $tasa = TasaComision::findOrFail($id);
        $tasa->delete();

        return redirect()->back()->with('success', 'Tasa de comisión eliminada correctamente.');
    }

    /**
     * Muestra la vista de liquidaciones e historial de honorarios.
     */
    public function liquidaciones()
    {
        $dentistas = Doctor::with('usuario')->get();

        $especialidadesDisponibles = [
            'Odontología General',
            'Endodoncia',
            'Ortodoncia',
            'Periodoncia',
            'Cirugía Bucal',
            'Implantología',
            'Rehabilitación Oral'
        ];

        $liquidaciones = Liquidacion::with('dentista')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('financiero.comisiones.liquidaciones', compact(
            'dentistas', 
            'liquidaciones', 
            'especialidadesDisponibles'
        ));
    }

    /**
     * Genera y guarda un nuevo registro de liquidación de honorarios.
     */
    public function generarLiquidacion(Request $request)
    {
        $request->validate([
            'dentista_id'     => 'required',
            'periodo_inicio'  => 'required|date',
            'periodo_fin'     => 'required|date|after_or_equal:periodo_inicio',
            'total_recaudado' => 'required|numeric|min:0',
            'total_comision'  => 'required|numeric|min:0',
        ]);

        Liquidacion::create([
            'dentista_id'     => $request->dentista_id,
            'periodo_inicio'  => $request->periodo_inicio,
            'periodo_fin'     => $request->periodo_fin,
            'total_recaudado' => $request->total_recaudado,
            'total_comision'  => $request->total_comision,
            'estado'          => 'pendiente',
        ]);

        return redirect()->back()->with('success', 'Liquidación generada con éxito.');
    }

    /**
     * Cambia el estado de una liquidación a "pagado" e ingresa la fecha actual.
     */
    public function pagarLiquidacion($id)
    {
        $liquidacion = Liquidacion::findOrFail($id);

        $liquidacion->update([
            'estado'     => 'pagado',
            'fecha_pago' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'La liquidación ha sido marcada como pagada.');
    }

    /**
     * Obtiene el total recaudado por un doctor en un rango de fechas a través de los pagos de sus presupuestos.
     */
    public function calcularRecaudacion(Request $request)
{
    $dentistaId = $request->input('dentista_id');
    $inicio = $request->input('periodo_inicio') ?? $request->input('fecha_inicio');
    $fin = $request->input('periodo_fin') ?? $request->input('fecha_fin');

    if (!$dentistaId || !$inicio || !$fin) {
        return response()->json([
            'total_recaudado' => 0,
            'porcentaje'      => 0,
            'total_comision'  => 0,
        ]);
    }

    // Buscamos si existe el Doctor relacionado al ID recibido
    $doctor = \App\Models\Doctor::where('usuario_id', $dentistaId)
        ->orWhere('id', $dentistaId)
        ->first();

    // Recopilamos todos los IDs posibles asociados a este profesional
    $ids = array_unique(array_filter([
        (int) $dentistaId,
        $doctor ? (int) $doctor->id : null,
        $doctor ? (int) $doctor->usuario_id : null,
    ]));

    // Aseguramos cubrir el rango horario completo del día
    $fechaInicio = \Carbon\Carbon::parse($inicio)->startOfDay()->toDateTimeString();
    $fechaFin    = \Carbon\Carbon::parse($fin)->endOfDay()->toDateTimeString();

    // Consultar recaudación cruzando presupuestos y pagos
    $totalRecaudado = \App\Models\Pago::where(function ($query) use ($ids) {
            $query->whereHas('presupuesto', function ($q) use ($ids) {
                $q->whereIn('dentista_id', $ids);
            })->orWhereIn('usuario_id', $ids);
        })
        ->whereBetween('fecha_pago', [$fechaInicio, $fechaFin])
        ->sum('monto');

    // Buscar la tasa de comisión correspondiente
    $tasa = \App\Models\TasaComision::whereIn('dentista_id', $ids)->first();
    $porcentaje = $tasa ? (float) $tasa->porcentaje : 0;

    $totalComision = ($totalRecaudado * $porcentaje) / 100;

    return response()->json([
        'total_recaudado' => (float) $totalRecaudado,
        'recaudado'       => (float) $totalRecaudado,
        'porcentaje'      => $porcentaje,
        'total_comision'  => (float) $totalComision,
        'comision'        => (float) $totalComision,
    ]);
}
}