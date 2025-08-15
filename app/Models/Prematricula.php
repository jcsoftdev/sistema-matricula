<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prematricula extends Model
{
    use HasFactory;
   protected $fillable = [
        'user_id',
        'dni_estudiante',
        'nombres_estudiante',
        'apellidos_estudiante',
        'genero',
        'fecha_nacimiento',
        'grado_postula',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
