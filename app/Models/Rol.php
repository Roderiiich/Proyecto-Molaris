<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    // Un rol tiene muchos usuarios
    public function usuarios()
    {
        return $this->hasMany(Usuario::class);
    }
}
