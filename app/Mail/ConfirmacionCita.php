<?php

namespace App\Mail;

use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL; // 👈 Importamos la fachada URL

class ConfirmacionCita extends Mailable
{
    use Queueable, SerializesModels;

    public $cita;
    public $fechaFormateada;
    public $nombreDoctor;
    public $urlConfirmacion; // 👈 Variable para la URL firmada

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;

        Carbon::setLocale('es');

        $this->fechaFormateada = ucfirst(
            Carbon::parse($cita->fecha_hora)
                ->translatedFormat('l d \d\e F \d\e Y \a \l\a\s H:i \h\r\s')
        );

        // Obtener el nombre del usuario asignado al doctor de forma segura
        $nombreUsuario = $cita->doctor->usuario->name 
            ?? $cita->doctor->usuario->nombre 
            ?? 'Especialista';

        $especialidad = $cita->doctor->especialidad ?? '';

        $this->nombreDoctor = "Dr. {$nombreUsuario}" . ($especialidad ? " ({$especialidad})" : "");

        // Generar la URL firmada única para confirmar o cancelar la cita
        $this->urlConfirmacion = URL::signedRoute(
            'citas.confirmar.paciente', 
            ['cita' => $cita->id]
        );
    }

    public function build()
    {
        return $this->subject('Confirmación de Cita Médica - Molaris')
                    ->html("
                        <div style='font-family: sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #ffffff;'>
                            <h2 style='color: #0f2d4a; margin-top: 0;'>¡Hola {$this->cita->paciente->nombre}!</h2>
                            <p>Tu cita médica ha sido agendada con éxito en <strong>Molaris</strong>.</p>
                            <p>Te esperamos para arreglar tu sonrisa.</p>
                            
                            <ul style='background-color: #f8fafc; padding: 15px 25px; border-radius: 8px; list-style: none; line-height: 1.8;'>
                                <li><strong>Fecha y Hora:</strong> {$this->fechaFormateada}</li>
                                <li><strong>Especialista:</strong> {$this->nombreDoctor}</li>
                                <li><strong>Box:</strong> {$this->cita->box->nombre}</li>
                            </ul>

                            <p style='font-size: 13px; color: #64748b; text-align: center;'>Por favor, responde a tu cita haciendo clic en el siguiente botón:</p>

                            <!-- BOTÓN DE CONFIRMACIÓN / CANCELACIÓN -->
                            <div style='text-align: center; margin: 25px 0;'>
                                <a href='{$this->urlConfirmacion}' 
                                   style='background-color: #0f2d4a; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; font-size: 14px; display: inline-block;'>
                                    Confirmar Mi Cita
                                </a>
                            </div>

                            <p style='font-size: 12px; color: #94a3b8; text-align: center;'>Por favor, llega con 10 minutos de anticipación.<br>Si prefieres, también puedes contactarnos a recepcion@clinicavenedental.cl</p>
                        </div>
                    ");
    }
}