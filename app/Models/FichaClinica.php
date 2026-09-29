<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichaClinica extends Model
{
    //
    protected $fillable = ['paciente_id', 'doctor_id', 'motivo_consulta', 'diagnostico', 'tratamiento', 'observaciones'];

    public function paciente()
    {
        return $this->belongsTo(Paciente::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
    public function adjuntos()
    {
        return $this->hasMany(FichaAdjunto::class);
    }
}
