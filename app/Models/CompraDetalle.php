<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompraDetalle extends Model
{
    use HasFactory;

    protected $table = 'compra_detalles';

    protected $fillable = [
        'compra_id',
        'articulo_id',
        'nombre_insumo',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    /**
     * Relación con la compra principal
     */
    public function compra()
    {
        return $this->belongsTo(Compra::class, 'compra_id');
    }

    /**
     * Relación con el artículo / ítem de inventario
     */
    public function articulo()
    {
        return $this->belongsTo(Articulo::class, 'articulo_id');
    }
}