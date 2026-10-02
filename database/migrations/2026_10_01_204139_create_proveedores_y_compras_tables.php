<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Proveedores de Insumos Clínicos
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('rut')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->integer('evaluacion')->default(5); // Calificación 1 a 5 estrellas
            $table->text('notas')->nullable();
            $table->timestamps();
        });

        // Registro de Compras
        Schema::create('compras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proveedor_id')->constrained('proveedores')->onDelete('cascade');
            $table->string('numero_factura')->nullable();
            $table->decimal('monto_total', 12, 2);
            $table->date('fecha_compra');
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compras');
        Schema::dropIfExists('proveedores');
    }
};
