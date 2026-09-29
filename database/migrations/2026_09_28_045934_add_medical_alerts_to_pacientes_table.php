<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->text('alergias')->nullable()->after('correo');
            $table->text('enfermedades_cronicas')->nullable()->after('alergias');
            $table->json('odontograma_state')->nullable()->after('enfermedades_cronicas');
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropColumn(['alergias', 'enfermedades_cronicas', 'odontograma_state']);
        });
    }
};