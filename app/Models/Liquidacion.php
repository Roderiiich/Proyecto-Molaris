<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liquidacion extends Model
{
    use HasFactory;

    protected $table = 'liquidaciones';

    protected $fillable = [
        'dentista_id',
        'periodo_inicio',
        'periodo_fin',
        'total_recaudado',
        'total_comision',
        'estado',
        'fecha_pago',
    ];

    protected $casts = [
        'periodo_inicio' => 'date',
        'periodo_fin'    => 'date',
        'fecha_pago'     => 'datetime',
    ];

    /**
     * Relación con la tabla users (modelo User)
     */
    public function dentista()
    {
        return $this->belongsTo(User::class, 'dentista_id');
    }
}