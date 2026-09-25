<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    // Define el nombre correcto de la tabla
    protected $table = 'doctores';

    public function usuario()
    {
        return $this->belongsTo(Usuario::class);
    }
}