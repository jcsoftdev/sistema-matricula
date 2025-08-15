<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $fillable = [
        'periodo_academico_id',
        'estudiante_id',
        'apoderado_id',
        'cod_matricula',
        'nivel',
        'grado',
        'seccion',
        'situacion',
        'procedencia',
        'ie_procedencia',
        'matricula_costo',
        'mensualidad',
        'descuento',
        'total',
        'deuda',
        'dia_pago',
        'parentesco',
        'status'
    ];

    public function estudiante(){
        return $this->belongsTo(Estudiante::class);
    }

    public function apoderado(){
        return $this->belongsTo(Apoderado::class);
    }

    public function periodoAcademico(){
        return $this->belongsTo(PeriodoAcademico::class);
    }

    public function pagos(){
        return $this->hasMany(Pago::class);
    }

    // Scopes for filtering by status
    public function scopePrematricula($query)
    {
        return $query->where('status', 'prematricula');
    }

    public function scopeMatricula($query)
    {
        return $query->where('status', 'matricula');
    }
}
