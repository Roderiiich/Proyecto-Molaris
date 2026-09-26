<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';

    // Permite guardar estos campos desde el formulario
    protected $fillable = [
        'rut',
        'nombre',
        'telefono',
        'correo',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}