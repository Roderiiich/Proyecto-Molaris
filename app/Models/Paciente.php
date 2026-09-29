<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';

    // Permite guardar estos campos desde el formulario
    protected $fillable = [
    'nombre',
    'rut',
    'telefono',
    'correo',
    'alergias',
    'enfermedades_cronicas',
    'odontograma_state',
];
    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }

    public function fichasClinicas()
    {
    return $this->hasMany(FichaClinica::class, 'paciente_id')->latest();
    }
}