<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MolarisDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Boxes (Box 1 y Box 2)
        DB::table('boxes')->insertOrIgnore([
            ['id' => 1, 'nombre' => 'Box 1', 'estado' => 'Disponible', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Box 2', 'estado' => 'Disponible', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Crear un Paciente de prueba
        DB::table('pacientes')->insertOrIgnore([
            [
                'id' => 1,
                'rut' => '12345678-9',
                'nombre' => 'Juan Pérez',
                'telefono' => '+56912345678',
                'correo' => 'juan.perez@example.com',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 3. Obtener un rol existente (Odontólogo o el primero que exista)
        $rol = DB::table('roles')->where('nombre', 'Odontólogo')->first() ?? DB::table('roles')->first();

        if ($rol) {
            // 4. Crear el registro en la tabla 'usuarios'
            DB::table('usuarios')->insertOrIgnore([
                [
                    'id' => 1,
                    'rol_id' => $rol->id,
                    'nombre' => 'Dr. Roberto Gómez',
                    'correo' => 'doctor@molaris.cl',
                    'contrasena' => Hash::make('password123'),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);

            // 5. Crear el registro en la tabla 'doctores' vinculado al usuario_id = 1
            DB::table('doctores')->insertOrIgnore([
                [
                    'id' => 1,
                    'usuario_id' => 1,
                    'rut' => '98765432-1',
                    'especialidad' => 'Odontología General',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }
    }
}