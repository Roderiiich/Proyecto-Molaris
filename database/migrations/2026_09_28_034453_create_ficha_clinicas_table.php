<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('ficha_clinicas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
        $table->foreignId('doctor_id')->nullable()->constrained('doctores')->onDelete('set null');
        $table->text('motivo_consulta');
        $table->text('diagnostico')->nullable();
        $table->text('tratamiento')->nullable();
        $table->text('observaciones')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ficha_clinicas');
    }
};
