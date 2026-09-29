<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Paciente;
use App\Models\FichaClinica;
use App\Models\Doctor;

class FichaClinicaSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtener el primer doctor disponible
        $doctor = Doctor::first();

        // 2. Crear un Paciente de Prueba explícito
        $paciente = Paciente::firstOrCreate(
            ['rut' => '12345678-9'],
            [
                'nombre'   => 'Carlos Mendoza Silva',
                'telefono' => '+56 9 8765 4321',
                'correo'   => 'carlos.mendoza@email.com',
            ]
        );

        // Limpiar atenciones previas de prueba si existen para no duplicar
        FichaClinica::where('paciente_id', $paciente->id)->delete();

        // 3. Crear Atencion 1 (Primera Consulta)
        FichaClinica::create([
            'paciente_id'     => $paciente->id,
            'doctor_id'       => $doctor->id ?? null,
            'motivo_consulta' => 'Molestia al masticar e hipersensibilidad al frío en zona posterior derecha.',
            'diagnostico'     => 'Caries oclusal en pieza 1.6 sin compromiso pulpar.',
            'tratamiento'     => 'Eliminación de tejido cariado y restauración con resina fotocurable.',
            'observaciones'   => 'Paciente refiere buena tolerancia al anestésico local. Se cita a control.',
            'created_at'      => now()->subDays(15), // Hace 15 días
        ]);

        // 4. Crear Atencion 2 (Control / Limpieza)
        FichaClinica::create([
            'paciente_id'     => $paciente->id,
            'doctor_id'       => $doctor->id ?? null,
            'motivo_consulta' => 'Control periódico y destartraje.',
            'diagnostico'     => 'Gingivitis marginal leve asociada a placa bacteriana.',
            'tratamiento'     => 'Profilaxis completa y destartraje supra e subgingival con ultrasonido.',
            'observaciones'   => 'Se indica reforzar técnica de cepillado e hilo dental diariamente.',
            'created_at'      => now(), // Hoy
        ]);

        $this->command->info("¡Paciente 'Carlos Mendoza Silva' creado/actualizado con 2 atenciones clínicas de prueba!");
    }
}