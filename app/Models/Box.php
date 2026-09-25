<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Box extends Model
{
    // Para evitar que busque "boxes" de forma incorrecta si aplica reglas plurales en inglés
    protected $table = 'boxes';
}