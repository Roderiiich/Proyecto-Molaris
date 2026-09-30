<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('articulos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('codigo_barras')->nullable();
            $table->string('categoria')->default('Insumo General');
            $table->string('unidad_medida')->default('Unidad');
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->decimal('precio_costo', 10, 2)->default(0.00);
            $table->enum('estado', ['Disponible', 'Agotado', 'Inactivo'])->default('Disponible');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articulos');
    }
};