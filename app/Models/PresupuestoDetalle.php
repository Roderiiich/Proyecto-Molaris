<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresupuestoDetalle extends Model
{
    use HasFactory;

    protected $fillable = [
        'presupuesto_id',
        'tratamiento_nombre',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    public function presupuesto()
    {
        return $this->belongsTo(Presupuesto::class);
    }
}