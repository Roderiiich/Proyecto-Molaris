<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DienteEstado extends Model
{
    use HasFactory;

    protected $table = 'diente_estados';

    protected $fillable = [
    'paciente_id',
    'numero_diente',
    'cara',
    'estado',
    'observacion',
];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }
}