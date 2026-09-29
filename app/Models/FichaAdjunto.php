<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichaAdjunto extends Model
{
    use HasFactory;

    protected $fillable = ['ficha_clinica_id', 'nombre_original', 'ruta_archivo', 'tipo_mime'];

    public function fichaClinica()
    {
        return $this->belongsTo(FichaClinica::class);
    }
}