<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmacionCita;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\Box;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CitaController extends Controller
{
    /**
     * Muestra la vista de agendamiento con la lista de citas y selects.
     */
    public function index()
    {
        // Traer las citas con las relaciones anidadas (doctor -> usuario)
        $citas = Cita::with(['paciente', 'doctor.usuario', 'box'])
            ->orderBy('fecha_hora', 'asc')
            ->get();

        // Traer catálogos para los selectores de la vista
        $pacientes = Paciente::orderBy('nombre', 'asc')->get();
        $doctores  = Doctor::with('usuario')->get();
        $boxes     = Box::orderBy('numero', 'asc')->get();

        return view('agenda.index', compact('citas', 'pacientes', 'doctores', 'boxes'));
    }

    /**
     * Guarda una nueva cita en la base de datos y envía el correo.
     */
    public function store(Request $request)
    {
        // 1. Combinar fecha y hora provenientes de la vista antes de validar
        if ($request->filled('fecha') && $request->filled('hora')) {
            $request->merge([
                'fecha_hora' => $request->fecha . ' ' . $request->hora
            ]);
        }

        // 2. Validación de datos obligatorios
        $request->validate([
            'paciente_id' => 'required|exists:pacientes,id',
            'doctor_id'   => 'required|exists:doctores,id',
            'box_id'      => 'required|exists:boxes,id',
            'fecha_hora'  => 'required|date',
        ], [
            'paciente_id.required' => 'Debe seleccionar un paciente usando el buscador.',
            'fecha_hora.required'  => 'Debe indicar tanto la fecha como la hora de la cita.',
        ]);

        // 3. Validación de colisiones horarias (RFS-02)
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

        // 4. Guardar cita
        $cita = Cita::create([
            'paciente_id' => $request->paciente_id,
            'doctor_id'   => $request->doctor_id,
            'box_id'      => $request->box_id,
            'fecha_hora'  => $request->fecha_hora,
            'estado'      => 'Confirmada',
        ]);

        // 5. Cargar relaciones para la plantilla de correo
        $cita->load(['paciente', 'doctor.usuario', 'box']);

        // 6. Enviar notificación por correo electrónico
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