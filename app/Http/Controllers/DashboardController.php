<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\Box;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today();

        // 1. Métricas / KPIs
        $citasHoyCount = Cita::whereDate('fecha_hora', $hoy)->count();
        $pacientesTotal = Paciente::count();
        $doctoresTotal = Doctor::count();
        $boxesTotal = Box::count();

        // 2. Próximas 5 citas del día actual
        $citasHoy = Cita::with(['paciente', 'doctor.usuario', 'box'])
            ->whereDate('fecha_hora', $hoy)
            ->orderBy('fecha_hora', 'asc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'citasHoyCount', 
            'pacientesTotal', 
            'doctoresTotal', 
            'boxesTotal', 
            'citasHoy'
        ));
    }
}