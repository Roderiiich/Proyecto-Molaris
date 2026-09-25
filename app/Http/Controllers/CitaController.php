<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmacionCita;
use App\Models\Cita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CitaController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'doctor_id'   => 'required|exists:doctores,id',
            'box_id'      => 'required|exists:boxes,id',
            'fecha_hora'  => 'required|date',
        ]);

        // Validación de colisiones horarias (RFS-02)
        $colisionBox = Cita::where('box_id', $request->box_id)
            ->where('fecha_hora', $request->fecha_hora)
            ->exists();

        $colisionDoctor = Cita::where('doctor_id', $request->doctor_id)
            ->where('fecha_hora', $request->fecha_hora)
            ->exists();

        if ($colisionBox) {
            return back()->withErrors(['error' => 'El box seleccionado ya está ocupado en ese horario.'])->withInput();
        }

        if ($colisionDoctor) {
            return back()->withErrors(['error' => 'El odontólogo ya tiene una cita en ese horario.'])->withInput();
        }

        // Guardar cita de forma segura
        $citaData = $request->only(['paciente_id', 'doctor_id', 'box_id', 'fecha_hora']);
        $citaData['estado'] = 'Confirmada';

        $cita = Cita::create($citaData);

        // Cargar relaciones para el correo
        $cita->load(['paciente', 'doctor.usuario', 'box']);

        // Enviar correo de confirmación con captura de errores
        $correoEnviado = false;
        if ($cita->paciente && $cita->paciente->correo) {
            try {
                Mail::to($cita->paciente->correo)->send(new ConfirmacionCita($cita));
                $correoEnviado = true;
            } catch (\Exception $e) {
                Log::error('Error al enviar correo de confirmación: ' . $e->getMessage());
            }
        }

        $mensaje = $correoEnviado 
            ? 'Cita agendada correctamente y notificación enviada por correo.' 
            : 'Cita agendada correctamente (No se pudo entregar el correo electrónico).';

        return redirect()->back()->with('success', $mensaje);
    }
}