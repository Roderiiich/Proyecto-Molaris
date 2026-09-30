<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    use HasFactory;

    protected $table = 'articulos';

    protected $fillable = [
        'nombre',
        'codigo_barras',
        'categoria',
        'unidad_medida',
        'stock_actual',
        'stock_minimo',
        'precio_costo',
        'estado',
    ];

    // Evalúa y actualiza automáticamente el estado según el stock
    public function actualizarEstado(): void
    {
        if ($this->stock_actual <= 0) {
            $this->estado = 'Agotado';
        } else {
            $this->estado = 'Disponible';
        }
        $this->save();
    }

    public function fichasClinicas()
    {
        return $this->belongsToMany(FichaClinica::class, 'articulo_ficha_clinica')
                    ->withPivot('cantidad')
                    ->withTimestamps();
    }
}