<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedores';

    protected $fillable = [
        'nombre',
        'rut',
        'telefono',
        'email',
        'evaluacion',
        'notas',
    ];

    /**
     * Relación: Un proveedor tiene muchas compras.
     */
    public function compras()
    {
        return $this->hasMany(Compra::class, 'proveedor_id');
    }
}