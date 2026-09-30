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
    // 1. Combinar fecha y hora
    if ($request->filled('fecha') && $request->filled('hora')) {
        $request->merge([
            'fecha_hora' => $request->fecha . ' ' . $request->hora
        ]);
    }

    // 2. Validación
    $request->validate([
        'paciente_id' => 'required|exists:pacientes,id',
        'doctor_id'   => 'required|exists:doctores,id',
        'box_id'      => 'required|exists:boxes,id',
        'fecha_hora'  => 'required|date',
    ], [
        'paciente_id.required' => 'Debe seleccionar un paciente usando el buscador.',
        'fecha_hora.required'  => 'Debe indicar tanto la fecha como la hora de la cita.',
    ]);

    // 3. Validación de colisiones horarias
    $colisionBox = Cita::where('box_id', $request->box_id)
        ->where('fecha_hora', $request->fecha_hora)
        ->exists();

    $colisionDoctor = Cita::where('doctor_id', $request->doctor_id)
        ->where('fecha_hora', $request->fecha_hora)
        ->exists();

    if ($colisionBox) {
        return back()
            ->withErrors([
                'error' => 'El box seleccionado ya está ocupado en ese horario.'
            ])
            ->withInput();
    }

    if ($colisionDoctor) {
        return back()
            ->withErrors([
                'error' => 'El odontólogo ya tiene una cita en ese horario.'
            ])
            ->withInput();
    }

    // 4. Guardar cita
    $cita = Cita::create([
        'paciente_id' => $request->paciente_id,
        'doctor_id'   => $request->doctor_id,
        'box_id'      => $request->box_id,
        'fecha_hora'  => $request->fecha_hora,
        'estado'      => 'Confirmada',
    ]);

    // 5. Cargar relaciones
    $cita->load([
        'paciente',
        'doctor.usuario',
        'box'
    ]);

    // 6. Diagnóstico del correo
    $correoEnviado = false;

    if (!$cita->paciente) {

        Log::error('ERROR CORREO: La cita no tiene paciente asociado.', [
            'cita_id' => $cita->id,
        ]);

    } elseif (!$cita->paciente->correo) {

        Log::warning('ERROR CORREO: El paciente no tiene correo registrado.', [
            'cita_id' => $cita->id,
            'paciente_id' => $cita->paciente->id,
        ]);

    } else {

        try {

            Log::info('CORREO: Intentando enviar confirmación.', [
                'cita_id' => $cita->id,
                'correo_destino' => $cita->paciente->correo,
            ]);

            Mail::to($cita->paciente->correo)
                ->send(new ConfirmacionCita($cita));

            $correoEnviado = true;

            Log::info('CORREO: Confirmación enviada correctamente.', [
                'cita_id' => $cita->id,
                'correo_destino' => $cita->paciente->correo,
            ]);

        } catch (\Throwable $e) {

            Log::error('CORREO: Error al enviar confirmación.', [
                'cita_id' => $cita->id,
                'correo_destino' => $cita->paciente->correo,
                'error' => $e->getMessage(),
                'archivo' => $e->getFile(),
                'linea' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }

    // 7. Mensaje final
    if ($correoEnviado) {

        $mensaje = 'Cita agendada correctamente y notificación enviada por correo.';

    } else {

        $mensaje = 'Cita agendada correctamente, pero no se pudo enviar el correo electrónico.';
    }

    return redirect()
        ->back()
        ->with('success', $mensaje);
}

    
}