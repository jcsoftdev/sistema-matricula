<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodoAcademico extends Model
{
    use HasFactory;

    protected $table = 'periodos_academicos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'fecha_inicio_prematricula',
        'fecha_fin_prematricula',
        'activo',
        'costo_matricula',
        'costo_mensualidad',
        'descuento_maximo'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'fecha_inicio_prematricula' => 'date',
        'fecha_fin_prematricula' => 'date',
        'activo' => 'boolean',
        'costo_matricula' => 'decimal:2',
        'costo_mensualidad' => 'decimal:2',
        'descuento_maximo' => 'decimal:2'
    ];

    // Relationships
    public function matriculas()
    {
        return $this->hasMany(Matricula::class);
    }

    // Scopes
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    // Static methods
    public static function getActivo()
    {
        return self::where('activo', true)->first();
    }

    public static function activarPeriodo($id)
    {
        // Deactivate all periods
        self::query()->update(['activo' => false]);

        // Activate the specified period
        return self::where('id', $id)->update(['activo' => true]);
    }

    // Helper methods
    public function isPrematriculaAberta()
    {
        $now = now()->toDateString();
        return $this->activo &&
               $this->fecha_inicio_prematricula &&
               $this->fecha_fin_prematricula &&
               $now >= $this->fecha_inicio_prematricula &&
               $now <= $this->fecha_fin_prematricula;
    }

    // Cost calculation methods
    public function getCostoMatriculaAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getCostoMensualidadAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getDescuentoMaximoAttribute($value)
    {
        return number_format($value, 2);
    }

    public function aplicarDescuento($monto, $porcentajeDescuento = 0)
    {
        $descuentoMaximo = min($porcentajeDescuento, $this->descuento_maximo);
        $descuento = ($monto * $descuentoMaximo) / 100;
        return $monto - $descuento;
    }

    public function getCostoMatriculaConDescuento($porcentajeDescuento = 0)
    {
        return $this->aplicarDescuento($this->costo_matricula, $porcentajeDescuento);
    }

    public function getCostoMensualidadConDescuento($porcentajeDescuento = 0)
    {
        return $this->aplicarDescuento($this->costo_mensualidad, $porcentajeDescuento);
    }
}
