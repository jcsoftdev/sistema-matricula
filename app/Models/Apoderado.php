<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apoderado extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'dni_apoderado',
        'nombres_apoderado',
        'apellidos_apoderado',
        'celular_apoderado',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function matriculas(){
        return $this->hasMany(Matricula::class);
    }

    public function estudiantes(){
        return $this->hasMany(Estudiante::class);
    }

    // Accessor for full name
    public function getNombreCompletoAttribute()
    {
        return trim($this->nombres_apoderado . ' ' . $this->apellidos_apoderado);
    }

    // Accessor for phone number (for compatibility)
    public function getTelefonoAttribute()
    {
        return $this->celular_apoderado;
    }
}
