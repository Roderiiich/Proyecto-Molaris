<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiquidacionComision extends Model
{
    use HasFactory;

    // Nombre explícito de la tabla
    protected $table = 'liquidaciones_comisiones';

    // Campos permitidos para asignación masiva
    protected $fillable = [
        'doctor_id', // O dentista_id segun tu migracion (ej: doctors_table)
        'periodo_inicio',
        'periodo_fin',
        'monto_total',
        'estado',      // Ej: 'pendiente', 'pagado'
        'fecha_pago',
        'observaciones',
    ];

    // Relación con el profesional (Doctor/Dentista)
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}