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
        Schema::create('doctores', function (Blueprint $table) {
    $table->id();
    // Apuntamos explícitamente a la tabla 'users'
    $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
    $table->string('rut')->unique();
    $table->string('especialidad');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
