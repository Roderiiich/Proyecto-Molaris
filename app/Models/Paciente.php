<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paciente extends Model
{
    protected $table = 'pacientes';

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}