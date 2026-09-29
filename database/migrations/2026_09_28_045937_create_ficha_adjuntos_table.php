<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ficha_adjuntos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ficha_clinica_id');
            $table->string('nombre_original');
            $table->string('ruta_archivo');
            $table->string('tipo_mime')->nullable();
            $table->timestamps();

            $table->index('ficha_clinica_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ficha_adjuntos');
    }
};