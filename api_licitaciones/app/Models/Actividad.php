<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actividad extends Model
{
    use HasFactory;

    protected $table = 'actividades';

    public $timestamps = false;

    protected $fillable = [
        'codigo_segmento',
        'segmento',
        'codigo_familia',
        'familia',
        'codigo_clase',
        'clase',
        'codigo_producto',
        'producto',
    ];

    protected $casts = [
        'codigo_segmento' => 'integer',
        'codigo_familia' => 'integer',
        'codigo_clase' => 'integer',
        'codigo_producto' => 'integer',
    ];

    /**
     * Relación: Una actividad tiene muchas ofertas
     */
    public function ofertas()
    {
        return $this->hasMany(Oferta::class, 'actividad_id');
    }
}