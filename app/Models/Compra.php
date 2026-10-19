<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    use HasFactory;

    protected $table = 'compras';

    protected $fillable = [
        'proveedor_id',
        'numero_factura',
        'monto_total',
        'fecha_compra',
        'descripcion',
    ];

    /**
     * Relación: Cada compra pertenece a un proveedor.
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    /**
     * Relación: Una compra tiene sus ítems/insumos detallados.
     */
    public function detalles()
    {
        return $this->hasMany(CompraDetalle::class, 'compra_id');
    }
}