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
       Schema::create('usuarios', function (Blueprint $table) {
        $table->id();
        $table->foreignId('rol_id')->constrained('roles')->onDelete('cascade');
        $table->string('nombre', 255);
        $table->string('correo', 255)->unique();
        $table->string('contrasena', 255); // Se encriptará con Bcrypt
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
