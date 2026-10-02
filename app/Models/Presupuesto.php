<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Presupuesto extends Model
{
    use HasFactory;

    protected $table = 'presupuestos';

    protected $fillable = [
        'paciente_id',
        'dentista_id',
        'monto_total',
        'monto_pagado',
        'saldo_pendiente',
        'estado',
        'motivo_anulacion',
        'fecha_anulacion',
    ];

    protected $casts = [
        'monto_total'     => 'decimal:2',
        'monto_pagado'    => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
        'fecha_anulacion' => 'datetime',
    ];

    // ------------------------------------------------------------------
    // Relaciones
    // ------------------------------------------------------------------

    /**
     * Paciente asociado al presupuesto.
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }

    /**
     * Odontólogo que generó el presupuesto.
     */
    public function dentista(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'dentista_id');
    }

    /**
     * Historial de abonos y pagos realizados.
     */
    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'presupuesto_id');
    }

    /**
     * Detalles o prestaciones del presupuesto.
     */
    public function detalles(): HasMany
    {
        return $this->hasMany(PresupuestoDetalle::class, 'presupuesto_id');
    }

    // ------------------------------------------------------------------
    // Atributos calculados
    // ------------------------------------------------------------------

    /**
     * Saldo pendiente del presupuesto.
     *
     * Se calcula como:
     * monto_total - monto_pagado
     */
    protected function saldoPendiente(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {

                $total = (float) ($attributes['monto_total'] ?? 0);
                $pagado = (float) ($attributes['monto_pagado'] ?? 0);

                return max(0, $total - $pagado);
            }
        );
    }

    /**
     * Calcula el porcentaje del total que ya ha sido pagado.
     */
    protected function porcentajePagado(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {

                $total = (float) ($attributes['monto_total'] ?? 0);
                $pagado = (float) ($attributes['monto_pagado'] ?? 0);

                if ($total <= 0) {
                    return 0;
                }

                return min(
                    100,
                    round(($pagado / $total) * 100, 1)
                );
            }
        );
    }
}