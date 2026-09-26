<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Box;
use App\Models\Rol;
use App\Models\User; // Cambiado de Usuario a User para Breeze
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. Crear los Boxes de atención
        Box::insert([
            ['nombre' => 'Box 1', 'estado' => 'Disponible'],
            ['nombre' => 'Box 2', 'estado' => 'Disponible'],
        ]);

        // 2. Crear los Roles del sistema
        $rolAdmin = Rol::create(['nombre' => 'Administrador', 'descripcion' => 'Jefe de Clínica']);
        $rolDoctor = Rol::create(['nombre' => 'Odontólogo Especialista', 'descripcion' => 'Doctor']);
        $rolRecepcion = Rol::create(['nombre' => 'Recepción', 'descripcion' => 'Secretaría']);

        // 3. Crear el Usuario para el Odontólogo usando las columnas nativas de Breeze (name, email, password)
        $usuarioDoctor = User::create([
            'rol_id'   => $rolDoctor->id,
            'name'     => 'Dr. Andrés Bello',
            'email'    => 'doctor@molaris.cl',
            'password' => Hash::make('password123'),
        ]);

        // 4. Crear el perfil de Doctor vinculado al ID del User recién generado
        Doctor::create([
            'usuario_id'   => $usuarioDoctor->id,
            'rut'          => '15666777-8',
            'especialidad' => 'Ortodoncia',
        ]);
    }
}