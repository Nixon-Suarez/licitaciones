<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model{
    use HasFactory;

    protected $table = 'usuario';

    const CREATED_AT = 'usuario_creado';
    const UPDATED_AT = 'usuario_actualizado';

    protected $fillable = [
        'usuario_nombre',
        'usuario_apellido',
        'usuario_usuario',
        'usuario_clave',
    ];

    protected $hidden = [
        'usuario_clave',
    ];

    protected $casts = [
        'usuario_creado' => 'datetime',
        'usuario_actualizado' => 'datetime',
    ];

    /**
     * Obtener el nombre del campo de password para autenticación
     */
    public function getAuthPassword()
    {
        return $this->usuario_clave;
    }

    /**
     * Obtener el nombre del campo de username para autenticación
     */
    public function getAuthIdentifierName()
    {
        return 'usuario_usuario';
    }
}