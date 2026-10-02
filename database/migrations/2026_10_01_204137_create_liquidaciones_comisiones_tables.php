<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tasas de Comisión por Odontólogo y Especialidad
        Schema::create('tasas_comisiones', function (Blueprint $table) {
            $table->id();
            // Apuntar a la tabla 'users' y eliminar en cascada si se borra el usuario
            $table->foreignId('dentista_id')->constrained('users')->onDelete('cascade');
            $table->string('especialidad');
            $table->decimal('porcentaje', 5, 2); // Ej: 45.00 %
            $table->timestamps();
        });

        // Liquidaciones de Honorarios por Periodo
        Schema::create('liquidaciones', function (Blueprint $table) {
            $table->id();
            // Apuntar a la tabla 'users' y eliminar en cascada si se borra el usuario
            $table->foreignId('dentista_id')->constrained('users')->onDelete('cascade');
            $table->date('periodo_inicio');
            $table->date('periodo_fin');
            $table->decimal('total_recaudado', 12, 2);
            $table->decimal('total_comision', 12, 2);
            $table->enum('estado', ['pendiente', 'pagado'])->default('pendiente');
            $table->timestamp('fecha_pago')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('liquidaciones');
        Schema::dropIfExists('tasas_comisiones');
    }
};