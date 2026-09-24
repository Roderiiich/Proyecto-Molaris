<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cita;

class CitaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'doctor_id' => 'required|exists:doctores,id',
            'box_id' => 'required|exists:boxes,id',
            'fecha_hora' => 'required|date',
        ]);

        // Validación de colisiones horarias (RFS-02)
        $colisionBox = Cita::where('box_id', $request->box_id)
            ->where('fecha_hora', $request->fecha_hora)
            ->exists();

        $colisionDoctor = Cita::where('doctor_id', $request->doctor_id)
            ->where('fecha_hora', $request->fecha_hora)
            ->exists();

        if ($colisionBox) {
            return back()->withErrors(['error' => 'El box seleccionado ya está ocupado en ese horario.']);
        }

        if ($colisionDoctor) {
            return back()->withErrors(['error' => 'El odontólogo ya tiene una cita en ese horario.']);
        }

        Cita::create($request->all());

        return redirect()->back()->with('success', 'Cita agendada correctamente.');
    }
}