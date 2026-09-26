<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cita extends Model
{
    use HasFactory;

    protected $table = 'citas';

    // Campos permitidos para la asignación masiva
    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'box_id',
        'fecha_hora',
        'estado'
    ];

    // Relación con Paciente
    public function paciente()
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    // Relación con Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

    // Relación con Box
    public function box()
    {
        return $this->belongsTo(Box::class, 'box_id');
    }
}