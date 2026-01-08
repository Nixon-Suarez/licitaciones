<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfertaDocumento extends Model
{
    use HasFactory;

    protected $table = 'ofertas_documentos';

    const CREATED_AT = 'creado_en';
    const UPDATED_AT = null;

    protected $fillable = [
        'licitacion_id',
        'titulo',
        'descripcion',
        'archivo',
        'ruta_archivo',
    ];

    protected $casts = [
        'licitacion_id' => 'integer',
        'creado_en' => 'datetime',
    ];

    /**
     * Relación: Un documento pertenece a una oferta
     */
    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'licitacion_id');
    }
}