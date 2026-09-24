<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    // Un usuario pertenece a un rol
    public function rol()
    {
        return $this->belongsTo(Rol::class);
    }

    // Un usuario puede ser un doctor
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }
}