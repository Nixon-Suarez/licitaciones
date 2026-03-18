<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    use HasFactory;

    protected $table = 'ofertas';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = 'actualizado_en';

    protected $fillable = [
        'consecutivo',
        'objeto',
        'descripcion',
        'moneda',
        'presupuesto',
        'actividad_id',
        'fecha_inicio',
        'hora_inicio',
        'fecha_cierre',
        'hora_cierre',
        'estado',
    ];

    protected $casts = [
        'presupuesto' => 'decimal:2',
        'actividad_id' => 'integer',
        'fecha_inicio' => 'date',
        'fecha_cierre' => 'date',
        'creado_en' => 'datetime',
        'actualizado_en' => 'datetime',
    ];

    /**
     * Relación: Una oferta pertenece a una actividad
     */
    public function actividad()
    {
        return $this->belongsTo(Actividad::class, 'actividad_id');
    }

    /**
     * Relación: Una oferta tiene muchos documentos
     */
    public function documentos()
    {
        return $this->hasMany(OfertaDocumento::class, 'licitacion_id');
    }
}