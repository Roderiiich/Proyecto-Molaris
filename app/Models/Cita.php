<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    // Se especifican los campos permitidos para la asignación masiva
    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'box_id',
        'fecha_hora',
        'estado'
    ];

    // Relaciones del modelo
    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function box()
    {
        return $this->belongsTo(Box::class);
    }
}