<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Box;
use App\Models\Cita;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AgendaController extends Controller
{
    /**
     * Muestra la vista de administración (index.blade.php)
     */
    public function index(Request $request)
    {
        $fecha = $request->input('fecha', Carbon::today()->format('Y-m-d'));
        $doctorId = $request->input('doctor_id');
        $boxId = $request->input('box_id');
        $estado = $request->input('estado');

        $citas = Cita::with(['paciente', 'doctor.usuario', 'box'])
            ->when($fecha, fn($q) => $q->whereDate('fecha_hora', $fecha))
            ->when($doctorId, fn($q) => $q->where('doctor_id', $doctorId))
            ->when($boxId, fn($q) => $q->where('box_id', $boxId))
            ->when($estado, fn($q) => $q->where('estado', $estado))
            ->orderBy('fecha_hora', 'asc')
            ->get();

        $doctores = Doctor::with('usuario')->get();
        $boxes = Box::all();

        return view('agenda.index', compact('citas', 'doctores', 'boxes', 'fecha'));
    }

    /**
     * Muestra la vista de agenda general para Dentistas (agenda-general.blade.php)
     */
    public function general(Request $request)
    {
        $fecha = $request->input('fecha', Carbon::today()->format('Y-m-d'));
        $doctorId = $request->input('doctor_id');
        $boxId = $request->input('box_id');
        $estado = $request->input('estado');

        $citas = Cita::with(['paciente', 'doctor.usuario', 'box'])
            ->when($fecha, fn($q) => $q->whereDate('fecha_hora', $fecha))
            ->when($doctorId, fn($q) => $q->where('doctor_id', $doctorId))
            ->when($boxId, fn($q) => $q->where('box_id', $boxId))
            ->when($estado, fn($q) => $q->where('estado', $estado))
            ->orderBy('fecha_hora', 'asc')
            ->get();

        $doctores = Doctor::with('usuario')->get();
        $boxes = Box::all();

        return view('agenda.agenda-general', compact('citas', 'doctores', 'boxes', 'fecha'));
    }

    public function confirmar($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update(['estado' => 'confirmada']);

        return redirect()->back()->with('success', 'La cita fue confirmada con éxito.');
    }

    public function rechazar($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->update(['estado' => 'rechazada']);

        return redirect()->back()->with('success', 'La cita fue rechazada.');
    }

    public function miAgenda(Request $request)
    {
        $user = auth()->user();
        $doctor = Doctor::where('usuario_id', $user->id)->first();

        if (!$doctor) {
            abort(403, 'No tienes una ficha de Odontólogo asociada.');
        }

        $fechaInicio = $request->input('fecha_inicio') 
            ? Carbon::parse($request->input('fecha_inicio'))->startOfWeek() 
            : Carbon::now()->startOfWeek();

        $fechaFin = (clone $fechaInicio)->addDays(5)->endOfDay();

        $citas = Cita::with(['paciente', 'box'])
            ->where('doctor_id', $doctor->id)
            ->whereBetween('fecha_hora', [$fechaInicio->startOfDay(), $fechaFin])
            ->whereNotIn('estado', ['Cancelada', 'Rechazada'])
            ->get();

        return view('agenda.mi-agenda', compact('doctor', 'citas', 'fechaInicio', 'fechaFin'));
    }
}