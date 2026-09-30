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
    Schema::create('articulo_ficha_clinica', function (Blueprint $table) {
        $table->id();
        
        // Apuntamos explícitamente a la tabla 'ficha_clinicas'
        $table->foreignId('ficha_clinica_id')
              ->constrained('ficha_clinicas')
              ->onDelete('cascade');
              
        // Apuntamos explícitamente a la tabla 'articulos'
        $table->foreignId('articulo_id')
              ->constrained('articulos')
              ->onDelete('cascade');

        $table->integer('cantidad')->default(1);
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('atencion_insumo');
    }
};
