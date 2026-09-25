<?php

namespace App\Mail;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmacionCita extends Mailable
{
    use Queueable, SerializesModels;

    public $cita;

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    public function build()
    {
        return $this->subject('Confirmación de Cita Médica - Molaris')
                    ->html("
                        <h2>¡Hola {$this->cita->paciente->nombre}!</h2>
                        <p>Tu cita médica ha sido agendada con éxito.</p>
                        <ul>
                            <li><strong>Fecha y Hora:</strong> {$this->cita->fecha_hora}</li>
                            <li><strong>Especialista:</strong> {$this->cita->doctor->usuario->nombre}</li>
                            <li><strong>Lugar:</strong> {$this->cita->box->nombre}</li>
                        </ul>
                        <p>Por favor, llega con 10 minutos de anticipación.</p>
                    ");
    }
}