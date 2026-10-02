<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'presupuesto_id',
        'usuario_id',
        'monto',
        'medio_pago',
        'numero_referencia',
        'fecha_pago',
    ];

    protected $casts = [
        'monto'      => 'decimal:2',
        'fecha_pago' => 'datetime',
    ];

    // ------------------------------------------------------------------
    // Relaciones
    // ------------------------------------------------------------------

    /**
     * Presupuesto al cual pertenece este cobro/abono.
     */
    public function presupuesto(): BelongsTo
    {
        return $this->belongsTo(Presupuesto::class, 'presupuesto_id');
    }

    /**
     * Usuario (Cajero / Administrador) que procesó el cobro.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // ------------------------------------------------------------------
    // Accesores y Atributos Calculados
    // ------------------------------------------------------------------

    /**
     * Formatea el nombre legible del medio de pago.
     */
    protected function medioPagoTexto(): Attribute
    {
        return Attribute::make(
            get: fn () => match($this->medio_pago) {
                'efectivo'      => 'Efectivo',
                'transferencia' => 'Transferencia Bancaria',
                'pos_transbank' => 'POS Transbank',
                default         => ucfirst($this->medio_pago),
            }
        );
    }
}