<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla de Presupuestos (debe crearse primero)
        Schema::create('presupuestos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained('pacientes');
            $table->foreignId('dentista_id')->nullable()->constrained('doctores');
            $table->decimal('monto_total', 12, 2)->default(0);
            $table->decimal('monto_pagado', 12, 2)->default(0);
            $table->decimal('saldo_pendiente', 12, 2)->default(0);
            $table->enum('estado', ['borrador', 'pendiente', 'completado', 'anulado'])->default('borrador');
            $table->string('motivo_anulacion')->nullable();
            $table->timestamp('fecha_anulacion')->nullable();
            $table->timestamps();
        });

        // 2. Tabla de Pagos (el código que te pasé)
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('presupuesto_id')
                  ->constrained('presupuestos')
                  ->onDelete('cascade');

            $table->foreignId('usuario_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->decimal('monto', 12, 2);
            $table->enum('medio_pago', ['efectivo', 'transferencia', 'pos_transbank'])->default('efectivo');
            $table->string('numero_referencia', 100)->nullable();
            $table->dateTime('fecha_pago')->useCurrent();
            
            $table->timestamps();

            $table->index('fecha_pago');
            $table->index(['presupuesto_id', 'fecha_pago']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
        Schema::dropIfExists('presupuestos');
    }
};