<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $fillable = [
        'dni_estudiante',
        'nombres_estudiante',
        'apellidos_estudiante',
        'genero',
        'fecha_nacimiento',
        'apoderado_id'
    ];

    public function matriculas(){
        return $this->hasMany(Matricula::class);
    }

    public function apoderado(){
        return $this->belongsTo(Apoderado::class);
    }

    // Accessor for full name
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombres_estudiante . ' ' . $this->apellidos_estudiante);
    }
}
