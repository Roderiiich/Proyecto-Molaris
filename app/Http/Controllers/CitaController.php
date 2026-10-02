<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmacionCita;
use App\Models\Cita;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\Box;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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

        // Traer catálogos para los selectores
        $pacientes = Paciente::orderBy('nombre', 'asc')->get();
        $doctores = Doctor::with('usuario')->get();
        $boxes = Box::orderBy('numero', 'asc')->get();

        return view(
            'agenda.index',
            compact('citas', 'pacientes', 'doctores', 'boxes')
        );
    }

    /**
     * Guarda una nueva cita en la base de datos y envía el correo mediante Brevo.
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
            ->whereNotIn('estado', ['Rechazada', 'rechazada']) // Ignorar citas rechazadas en colisiones
            ->exists();

        $colisionDoctor = Cita::where('doctor_id', $request->doctor_id)
            ->where('fecha_hora', $request->fecha_hora)
            ->whereNotIn('estado', ['Rechazada', 'rechazada']) // Ignorar citas rechazadas en colisiones
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

        // 4. Guardar cita como 'Pendiente' por defecto
        $cita = Cita::create([
            'paciente_id' => $request->paciente_id,
            'doctor_id'   => $request->doctor_id,
            'box_id'      => $request->box_id,
            'fecha_hora'  => $request->fecha_hora,
            'estado'      => 'Pendiente', // <--- Se asigna Pendiente para que nazca en gris
        ]);

        // 5. Cargar relaciones necesarias para el correo
        $cita->load([
            'paciente',
            'doctor.usuario',
            'box'
        ]);

        // 6. Enviar correo mediante Brevo
        $correoEnviado = false;

        // Verificar paciente
        if (!$cita->paciente) {
            Log::error('BREVO: La cita no tiene paciente asociado.', [
                'cita_id' => $cita->id,
            ]);

            return back()->withErrors([
                'error' => 'La cita fue creada, pero no se encontró el paciente asociado.'
            ]);
        }

        // Verificar correo del paciente
        if (!$cita->paciente->correo) {
            Log::warning('BREVO: El paciente no tiene correo registrado.', [
                'cita_id'     => $cita->id,
                'paciente_id' => $cita->paciente->id,
            ]);

            return back()->withErrors([
                'error' => 'La cita fue creada, pero el paciente no tiene un correo electrónico registrado.'
            ]);
        }

        try {
            Log::info('BREVO: Intentando enviar confirmación.', [
                'cita_id'        => $cita->id,
                'correo_destino' => $cita->paciente->correo,
            ]);

            // Crear instancia del correo
            $correo = new ConfirmacionCita($cita);

            // Obtener contenido HTML
            $html = $correo->contenidoHtml();

            // Enviar correo mediante API HTTPS de Brevo
            $respuesta = Http::withOptions([
                'verify' => false, // Omitir verificación SSL para entorno local (evita error cURL 60)
            ])->withHeaders([
                'accept'       => 'application/json',
                'api-key'      => env('BREVO_API_KEY'),
                'content-type' => 'application/json',
            ])->post('https://api.brevo.com/v3/smtp/email', [
                'sender' => [
                    'name'  => env('BREVO_FROM_NAME', 'Clínica Dental Venedental'),
                    'email' => env('BREVO_FROM_EMAIL'),
                ],
                'to' => [
                    [
                        'email' => $cita->paciente->correo,
                        'name'  => $cita->paciente->nombre,
                    ]
                ],
                'subject'     => $correo->asunto,
                'htmlContent' => $html,
            ]);

            // Revisar respuesta de Brevo
            if ($respuesta->successful()) {
                $correoEnviado = true;

                Log::info('BREVO: Correo enviado correctamente.', [
                    'cita_id'        => $cita->id,
                    'correo_destino' => $cita->paciente->correo,
                    'respuesta'      => $respuesta->json(),
                ]);
            } else {
                Log::error('BREVO: Error al enviar correo.', [
                    'cita_id'        => $cita->id,
                    'correo_destino' => $cita->paciente->correo,
                    'status'         => $respuesta->status(),
                    'respuesta'      => $respuesta->body(),
                ]);

                return back()->withErrors([
                    'error' => 'BREVO ERROR: ' . $respuesta->body()
                ]);
            }
        } catch (\Throwable $e) {
            Log::error('BREVO: Excepción al enviar correo.', [
                'cita_id'        => $cita->id,
                'correo_destino' => $cita->paciente->correo,
                'error'          => $e->getMessage(),
                'archivo'        => $e->getFile(),
                'linea'          => $e->getLine(),
            ]);

            return back()->withErrors([
                'error' => 'ERROR BREVO: ' . $e->getMessage()
            ]);
        }

        // 7. Mensaje final
        $mensaje = $correoEnviado
            ? 'Cita agendada correctamente y notificación enviada por correo.'
            : 'Cita agendada correctamente, pero no se pudo enviar el correo electrónico.';

        return redirect()
            ->back()
            ->with('success', $mensaje);
    }

    /**
     * Rechaza una cita y libera la hora en la agenda.
     */
    public function rechazarCita($id)
    {
        $cita = Cita::findOrFail($id);
        
        $cita->update([
            'estado' => 'Rechazada'
        ]);

        return back()->with('success', 'La cita ha sido rechazada y la hora se encuentra disponible nuevamente.');
    }
}