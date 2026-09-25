<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    public function paciente()
    {
        return $this->hasOne(Paciente::class);
    }
    
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }
}