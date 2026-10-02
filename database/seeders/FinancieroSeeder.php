<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Presupuesto;
use App\Models\Pago;
use App\Models\Paciente;
use App\Models\Doctor;
use App\Models\User;

class FinancieroSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtener o crear entidades base
        $paciente = Paciente::first() ?? Paciente::create([
            'nombres' => 'Carlos',
            'apellidos' => 'Mendoza Silva',
            'rut' => '15.432.890-1',
            'email' => 'carlos.mendoza@example.com',
            'telefono' => '+56987654321',
        ]);

        $doctor = Doctor::first() ?? Doctor::create([
            'nombre' => 'María',
            'apellido' => 'González Velozo',
            'especialidad' => 'Ortodoncia',
        ]);

        $usuario = User::first();

        // 2. Presupuesto 1: Completado (Totalmente Pagado)
        $p1 = Presupuesto::create([
            'paciente_id' => $paciente->id,
            'dentista_id' => $doctor->id,
            'monto_total' => 350000,
            'monto_pagado' => 350000,
            'saldo_pendiente' => 0,
            'estado' => 'completado',
        ]);

        Pago::create([
            'presupuesto_id' => $p1->id,
            'usuario_id' => $usuario?->id,
            'monto' => 350000,
            'medio_pago' => 'pos_transbank',
            'numero_referencia' => 'TX-987654',
            'fecha_pago' => now()->subDays(2),
        ]);

        // 3. Presupuesto 2: Pendiente (Con saldo y abonos parciales hoy)
        $p2 = Presupuesto::create([
            'paciente_id' => $paciente->id,
            'dentista_id' => $doctor->id,
            'monto_total' => 500000,
            'monto_pagado' => 200000,
            'saldo_pendiente' => 300000,
            'estado' => 'pendiente',
        ]);

        // Abono 1 (Efectivo)
        Pago::create([
            'presupuesto_id' => $p2->id,
            'usuario_id' => $usuario?->id,
            'monto' => 100000,
            'medio_pago' => 'efectivo',
            'numero_referencia' => null,
            'fecha_pago' => now(),
        ]);

        // Abono 2 (Transferencia)
        Pago::create([
            'presupuesto_id' => $p2->id,
            'usuario_id' => $usuario?->id,
            'monto' => 100000,
            'medio_pago' => 'transferencia',
            'numero_referencia' => 'TR-112233',
            'fecha_pago' => now(),
        ]);

        // 4. Presupuesto 3: Borrador
        Presupuesto::create([
            'paciente_id' => $paciente->id,
            'dentista_id' => $doctor->id,
            'monto_total' => 180000,
            'monto_pagado' => 0,
            'saldo_pendiente' => 180000,
            'estado' => 'borrador',
        ]);
    }
}