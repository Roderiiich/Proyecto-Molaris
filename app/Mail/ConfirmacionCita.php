<?php

namespace App\Mail;

use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ConfirmacionCita extends Mailable
{
    use Queueable, SerializesModels;

    public $cita;
    public $fechaFormateada;
    public $nombreDoctor;
    public $urlConfirmacion;
    public $asunto;

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;

        Carbon::setLocale('es');

        // Formateo de fecha soportando tanto fecha_hora como fecha individual
        $fechaBase = $cita->fecha_hora ?? $cita->fecha ?? now();
        $this->fechaFormateada = ucfirst(
            Carbon::parse($fechaBase)
                ->translatedFormat('l d \d\e F \d\e Y \a \l\a\s H:i \h\r\s')
        );

        // Nombre del doctor con fallback seguro
        $nombreUsuario = $cita->doctor?->usuario?->name
            ?? $cita->doctor?->usuario?->nombre
            ?? 'Especialista';

        $especialidad = $cita->doctor?->especialidad ?? '';

        $this->nombreDoctor = "Dr. {$nombreUsuario}"
            . ($especialidad ? " ({$especialidad})" : "");

        // Generar URL firmada con expiración de 48 horas (Recomendado)
        $this->urlConfirmacion = URL::temporarySignedRoute(
            'citas.confirmar.paciente',
            now()->addHours(48),
            ['cita' => $cita->id]
        );

        $this->asunto = 'Confirmación de Cita Médica - Molaris';
    }

    public function build()
    {
        return $this->subject($this->asunto)
            ->html($this->contenidoHtml());
    }

    /**
     * Genera el contenido HTML del correo.
     */
    public function contenidoHtml()
    {
        $nombrePaciente = $this->cita->paciente?->nombre ?? 'Estimado/a Paciente';
        $nombreBox = $this->cita->box?->nombre ?? 'Por asignar';

        return "
            <div style='font-family: Arial, Helvetica, sans-serif; max-width: 500px; margin: 0 auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 12px; background-color: #ffffff;'>

                <h2 style='color: #0f2d4a; margin-top: 0;'>
                    ¡Hola {$nombrePaciente}!
                </h2>

                <p style='color: #334155; line-height: 1.5;'>
                    Tu cita médica ha sido agendada con éxito en <strong>Molaris</strong>.
                    Te esperamos para cuidar de tu sonrisa.
                </p>

                <ul style='background-color: #f8fafc; padding: 15px 25px; border-radius: 8px; list-style: none; line-height: 1.8; margin: 20px 0;'>

                    <li>
                        <strong>Fecha y Hora:</strong>
                        {$this->fechaFormateada}
                    </li>

                    <li>
                        <strong>Especialista:</strong>
                        {$this->nombreDoctor}
                    </li>

                    <li>
                        <strong>Box / Sala:</strong>
                        {$nombreBox}
                    </li>

                </ul>

                <p style='font-size: 13px; color: #64748b; text-align: center;'>
                    Por favor, confirma o responde a tu cita haciendo clic en el siguiente botón:
                </p>

                <div style='text-align: center; margin: 25px 0;'>

                    <a href='{$this->urlConfirmacion}'
                       style='background-color: #0f2d4a; color: #ffffff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; font-size: 14px; display: inline-block;'>
                        Confirmar Mi Cita
                    </a>

                </div>

                <p style='font-size: 12px; color: #94a3b8; text-align: center; line-height: 1.5;'>
                    Por favor, llega con 10 minutos de anticipación.
                    <br>
                    Si tienes dudas, escríbenos a 
                    <a href='mailto:recepcion@clinicavenedental.cl' style='color: #0f2d4a;'>recepcion@clinicavenedental.cl</a>
                </p>

            </div>
        ";
    }
}