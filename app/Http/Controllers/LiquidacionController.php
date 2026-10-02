<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Presupuesto;
use App\Models\Pago;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LiquidacionController extends Controller
{
    /**
     * Despliega la vista principal de liquidación de comisiones.
     */
    public function index(Request $request)
    {
        // Mes y año seleccionados (por defecto mes y año actual)
        $mes = $request->input('mes', Carbon::now()->month);
        $anio = $request->input('anio', Carbon::now()->year);
        $dentistaId = $request->input('dentista_id');

        // Obtener listado de odontólogos para el filtro
        $dentistas = Usuario::orderBy('nombre')->get();

        // Consulta de recaudación por odontólogo y especialidad en el periodo
        $queryLiquidaciones = DB::table('pagos')
            ->join('presupuestos', 'pagos.presupuesto_id', '=', 'presupuestos.id')
            ->join('presupuesto_detalles', 'presupuestos.id', '=', 'presupuesto_detalles.presupuesto_id')
            ->join('usuarios', 'presupuestos.dentista_id', '=', 'usuarios.id')
            ->select(
                'usuarios.id as dentista_id',
                'usuarios.nombre as dentista_nombre',
                'usuarios.apellido as dentista_apellido',
                'presupuesto_detalles.especialidad',
                DB::raw('SUM(pagos.monto) as total_recaudado'),
                DB::raw('COUNT(DISTINCT presupuestos.id) as total_presupuestos')
            )
            ->whereMonth('pagos.fecha_pago', $mes)
            ->whereYear('pagos.fecha_pago', $anio)
            ->groupBy('usuarios.id', 'usuarios.nombre', 'usuarios.apellido', 'presupuesto_detalles.especialidad');

        if ($dentistaId) {
            $queryLiquidaciones->where('usuarios.id', $dentistaId);
        }

        $resumenEspecialidades = $queryLiquidaciones->get();

        // Agrupar resultados por odontólogo
        $liquidaciones = $resumenEspecialidades->groupBy('dentista_id')->map(function ($items) {
            $primerItem = $items->first();
            
            // Definición base de comisiones por especialidad (o configurable en BD)
            $porcentajesEspecialidad = [
                'Odontología General' => 0.40, // 40%
                'Endodoncia'          => 0.50, // 50%
                'Ortodoncia'          => 0.45, // 45%
                'Periodoncia'         => 0.45, // 45%
                'Cirugía Bucal'       => 0.50, // 50%
                'Implantología'       => 0.55, // 55%
            ];

            $detalleEspecialidades = $items->map(function ($item) use ($porcentajesEspecialidad) {
                $porcentaje = $porcentajesEspecialidad[$item->especialidad] ?? 0.40; // 40% por defecto
                $comisionCalculada = $item->total_recaudado * $porcentaje;

                return [
                    'especialidad'     => $item->especialidad,
                    'total_recaudado'  => $item->total_recaudado,
                    'porcentaje'       => $porcentaje * 100,
                    'monto_comision'   => $comisionCalculada,
                ];
            });

            $totalRecaudadoGeneral = $detalleEspecialidades->sum('total_recaudado');
            $totalComisionGeneral  = $detalleEspecialidades->sum('monto_comision');

            return [
                'dentista_id'            => $primerItem->dentista_id,
                'dentista_nombre'        => $primerItem->dentista_nombre . ' ' . $primerItem->dentista_apellido,
                'total_recaudado'        => $totalRecaudadoGeneral,
                'total_comision'         => $totalComisionGeneral,
                'detalle_especialidades' => $detalleEspecialidades,
            ];
        });

        return view('liquidaciones.index', compact(
            'liquidaciones',
            'dentistas',
            'mes',
            'anio',
            'dentistaId'
        ));
    }
}