<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['nombre' => 'Administrador', 'descripcion' => 'Jefe de Clínica con acceso total'],
            ['nombre' => 'Odontólogo', 'descripcion' => 'Odontólogo Especialista'],
            ['nombre' => 'Recepción', 'descripcion' => 'Personal de recepción y agendamiento'],
        ]);
    }
}