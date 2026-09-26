<?php

namespace App\Mail;

use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmacionCita extends Mailable
{
    use Queueable, SerializesModels;

    public $cita;
    public $fechaFormateada;

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;

        
        Carbon::setLocale('es');

        
        $this->fechaFormateada = ucfirst(
            Carbon::parse($cita->fecha_hora)
                ->translatedFormat('l d \d\e F \d\e Y \a \l\a\s H:i \h\r\s')
        );
    }

    public function build()
    {
        return $this->subject('Confirmación de Cita Médica - Molaris')
                    ->html("
                        <h2>¡Hola {$this->cita->paciente->nombre}!</h2>
                        <p>Tu cita médica ha sido agendada con éxito.</p>
                        <p>Te esperamos para arreglar tu sonrisa.</p>
                        <ul>
                            <li><strong>Fecha y Hora:</strong> {$this->fechaFormateada}</li>
                            <li><strong>Especialista:</strong> {$this->cita->doctor->usuario->nombre}</li>
                            <li><strong>Box:</strong> {$this->cita->box->nombre}</li>
                        </ul>
                        <p>Por favor, llega con 10 minutos de anticipación.</p>
                        <p>Si desea cancelar la cita, por favor contacte a recepcion@molaris.cl.</p>
                    ");
    }
}