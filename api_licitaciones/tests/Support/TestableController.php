<?php

declare(strict_types=1);

namespace Tests\Support;

use App\Http\Controllers\Controller;

/**
 * Expone los métodos protegidos de Controller para poder testearlos.
 */
final class TestableController extends Controller
{
    public function verificarDatosPublico(string $filtro, string $cadena): bool
    {
        return $this->verificarDatos($filtro, $cadena);
    }

    public function puedeGestionarRegistroPublico(object $usuario, $propietarioId): bool
    {
        return $this->puedeGestionarRegistro($usuario, $propietarioId);
    }

    public function filtrarRegistrosVisiblesPublico(object $query, object $usuario): object
    {
        return $this->filtrarRegistrosVisibles($query, $usuario);
    }
}
