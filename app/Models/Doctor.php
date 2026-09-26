<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctores';

    protected $fillable = ['usuario_id', 'rut', 'especialidad'];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}