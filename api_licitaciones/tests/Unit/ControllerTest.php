<?php

declare(strict_types=1);

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tests\Support\RecordingQuery;
use Tests\Support\TestableController;

final class ControllerTest extends TestCase
{
    private TestableController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new TestableController();
    }

    public function testLimpiarCadenaEliminaEtiquetasScript(): void
    {
        $this->assertSame('alert(1)', $this->controller->limpiarCadena('<script>alert(1)</script>'));
    }

    public function testLimpiarCadenaEliminaSentenciasSqlSinImportarMayusculas(): void
    {
        $this->assertSame('usuario', $this->controller->limpiarCadena('SELECT * FROM usuario'));
        $this->assertSame('x', $this->controller->limpiarCadena('drop table x'));
    }

    public function testLimpiarCadenaEliminaCaracteresPeligrosos(): void
    {
        $this->assertSame('abc', $this->controller->limpiarCadena('a;b::c'));
        $this->assertSame('holamundo', $this->controller->limpiarCadena('hola--mundo'));
        $this->assertSame('bhola/b', $this->controller->limpiarCadena('  <b>hola</b>  '));
    }

    public function testLimpiarCadenaConservaTextoNormal(): void
    {
        $this->assertSame('Oferta de prueba 2026', $this->controller->limpiarCadena('  Oferta de prueba 2026  '));
    }

    public function testLimpiarCadenaEscapaComillasHtml(): void
    {
        $this->assertSame('dijo &quot;hola&quot;', $this->controller->limpiarCadena('dijo "hola"'));
    }

    public function testVerificarDatosDevuelveFalseCuandoCoincide(): void
    {
        $this->assertFalse($this->controller->verificarDatosPublico('[a-zA-Z0-9]{4,20}', 'admin123'));
        $this->assertFalse($this->controller->verificarDatosPublico('\d{4}-\d{2}-\d{2}', '2026-10-26'));
        $this->assertFalse($this->controller->verificarDatosPublico('([01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?', '23:59'));
    }

    public function testVerificarDatosDevuelveTrueCuandoNoCoincide(): void
    {
        $this->assertTrue($this->controller->verificarDatosPublico('[a-zA-Z0-9]{4,20}', 'ab'));
        $this->assertTrue($this->controller->verificarDatosPublico('\d{4}-\d{2}-\d{2}', '26-10-2026'));
        $this->assertTrue($this->controller->verificarDatosPublico('([01]\d|2[0-3]):[0-5]\d(:[0-5]\d)?', '25:00'));
    }

    public function testPuedeGestionarRegistroPermiteAdministrador(): void
    {
        $admin = (object) ['id' => 99, 'perfil' => 1];

        $this->assertTrue($this->controller->puedeGestionarRegistroPublico($admin, 55));
    }

    public function testPuedeGestionarRegistroPermitePropietario(): void
    {
        $usuario = (object) ['id' => 7, 'perfil' => 3];

        $this->assertTrue($this->controller->puedeGestionarRegistroPublico($usuario, 7));
        $this->assertFalse($this->controller->puedeGestionarRegistroPublico($usuario, 8));
    }

    public function testPuedeGestionarRegistroRechazaPerfil2QueNoEsPropietario(): void
    {
        $usuario = (object) ['id' => 7, 'perfil' => 2];

        $this->assertFalse($this->controller->puedeGestionarRegistroPublico($usuario, 8));
    }

    public function testFiltrarRegistrosVisiblesLimitaAlPropietarioCuandoPerfilEs3(): void
    {
        $query = new RecordingQuery();
        $usuario = (object) ['id' => 42, 'perfil' => 3];

        $result = $this->controller->filtrarRegistrosVisiblesPublico($query, $usuario);

        $this->assertSame($query, $result);
        $this->assertSame([['usuario_id', 42, null]], $query->wheres);
    }

    public function testFiltrarRegistrosVisiblesNoAplicaFiltroParaOtrosPerfiles(): void
    {
        foreach ([1, 2] as $perfil) {
            $query = new RecordingQuery();
            $usuario = (object) ['id' => 42, 'perfil' => $perfil];

            $this->controller->filtrarRegistrosVisiblesPublico($query, $usuario);

            $this->assertSame([], $query->wheres, "El perfil $perfil no debería filtrar por propietario");
        }
    }
}
