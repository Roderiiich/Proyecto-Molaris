<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TasaComision extends Model
{
    use HasFactory;

    protected $table = 'tasas_comisiones';

    protected $fillable = [
        'dentista_id',
        'especialidad',
        'porcentaje',
    ];

    public function dentista()
    {
        return $this->belongsTo(Usuario::class, 'dentista_id');
    }
}