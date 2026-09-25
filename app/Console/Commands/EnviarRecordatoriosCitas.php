<?php

namespace App\Console\Commands;

use App\Mail\ConfirmacionCita; // Puedes usar una clase de Mailable dedicada si prefieres
use App\Models\Cita;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class EnviarRecordatoriosCitas extends Command
{
    protected $signature = 'citas:enviar-recordatorios';
    protected $description = 'Envía correos de recordatorio para las citas agendadas dentro de las próximas 24 horas';

    public function handle()
    {
        // Buscar citas confirmadas entre mañana a primera hora y el final del día
        $inicioManana = Carbon::tomorrow()->startOfDay();
        $finManana = Carbon::tomorrow()->endOfDay();

        $citas = Cita::with(['paciente', 'doctor.usuario', 'box'])
            ->where('estado', 'Confirmada')
            ->whereBetween('fecha_hora', [$inicioManana, $finManana])
            ->get();

        $enviados = 0;

        foreach ($citas as $cita) {
            if ($cita->paciente && $cita->paciente->correo) {
                try {
                    Mail::to($cita->paciente->correo)->send(new ConfirmacionCita($cita));
                    $enviados++;
                } catch (\Exception $e) {
                    $this->error("Error enviando recordatorio a {$cita->paciente->correo}: " . $e->getMessage());
                }
            }
        }

        $this->info("Proceso finalizado. Recordatorios enviados: {$enviados}");
    }
}