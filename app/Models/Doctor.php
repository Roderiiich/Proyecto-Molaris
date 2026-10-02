<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctores';

    protected $fillable = [
        'usuario_id',
        'rut',
        'especialidad',
    ];

    /**
     * Relación con el usuario en español
     */
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    /**
     * Mantener alias en inglés por compatibilidad
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}