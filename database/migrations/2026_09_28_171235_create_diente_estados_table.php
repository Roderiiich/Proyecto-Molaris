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
    Schema::create('diente_estados', function (Blueprint $table) {
        $table->id();
        // Relación con el paciente o ficha médica
        $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
        
        $table->integer('numero_diente'); // Ej: 18, 11, 21, 51, etc.
        $table->string('cara')->nullable(); // vest, ling, mes, dist, oc, o 'completo'
        $table->string('estado'); // sano, caries, obturado, ausente, endodoncia, etc.
        $table->text('observacion')->nullable();
        
        $table->timestamps();

        // Evita duplicados: Un paciente solo tiene un estado por diente y cara
        $table->unique(['paciente_id', 'numero_diente', 'cara']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diente_estados');
    }
};
