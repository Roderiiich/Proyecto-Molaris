<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->updateOrInsert(
            ['id' => 1],
            ['nombre' => 'Administrador', 'descripcion' => 'Acceso total a todas las áreas del sistema Molaris']
        );

        DB::table('roles')->updateOrInsert(
            ['id' => 2],
            ['nombre' => 'Dentista', 'descripcion' => 'Acceso exclusivo a agenda de pacientes y ficha clínica']
        );
    }
}
