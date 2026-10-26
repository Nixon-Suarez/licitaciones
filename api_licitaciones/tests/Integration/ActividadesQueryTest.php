<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\userController;
use App\Models\Actividad;
use App\Models\Usuario;
use Tests\Support\DatabaseTestCase;

final class ActividadesQueryTest extends DatabaseTestCase
{
    private ActividadesController $controller;
    private object $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ActividadesController();
        $this->admin = (object) ['id' => 1, 'perfil' => 1];
    }

    public function testGetActividadesSinIdDevuelveListaResumida(): void
    {
        $this->crearActividad();

        [$status, $json] = $this->capture(
            fn () => $this->controller->getActividades('', $this->admin)
        );

        $this->assertSame(200, $status);
        $this->assertSame(200, $json['code']);
        $this->assertCount(1, $json['data']);
        $this->assertSame(['id', 'producto', 'usuario_id'], array_keys($json['data'][0]));
    }

    public function testGetActividadesPorIdDevuelveElDetalle(): void
    {
        $actividad = $this->crearActividad();

        [$status, $json] = $this->capture(
            fn () => $this->controller->getActividades((string) $actividad->id, $this->admin)
        );

        $this->assertSame(200, $status);
        $this->assertSame(200, $json['code']);
        $this->assertSame($actividad->id, $json['data']['id']);
        $this->assertSame('Gatos', $json['data']['producto']);
        $this->assertSame('Material Vivo', $json['data']['segmento']);
    }

    public function testGetActividadesConIdInexistenteDevuelve404(): void
    {
        [$status, $json] = $this->capture(
            fn () => $this->controller->getActividades('999', $this->admin)
        );

        $this->assertSame(404, $status);
        $this->assertSame('La actividad no existe', $json['data']);
    }

    public function testListarActividadesPaginaYCalculaMetadatos(): void
    {
        $this->crearActividad(['codigo_producto' => 1, 'producto' => 'Uno']);
        $this->crearActividad(['codigo_producto' => 2, 'producto' => 'Dos']);
        $this->crearActividad(['codigo_producto' => 3, 'producto' => 'Tres']);

        [$status, $json] = $this->capture(
            fn () => $this->controller->listarActividadesControlador(1, 2, '', '', $this->admin)
        );

        $this->assertSame(200, $status);
        $this->assertSame(3, $json['data']['total']);
        $this->assertSame(2, $json['data']['paginas']);
        $this->assertSame(1, $json['data']['pagina_actual']);
        $this->assertSame(1, $json['data']['inicio']);
        $this->assertCount(2, $json['data']['datos']);
    }

    public function testListarActividadesFiltraPorSegmentoYProducto(): void
    {
        $this->crearActividad(['codigo_producto' => 1, 'segmento' => 'Material Vivo', 'producto' => 'Gatos']);
        $this->crearActividad(['codigo_producto' => 2, 'segmento' => 'Servicios', 'producto' => 'Mantenimiento']);

        [, $porSegmento] = $this->capture(
            fn () => $this->controller->listarActividadesControlador(1, 10, 'Material', '', $this->admin)
        );
        [, $porProducto] = $this->capture(
            fn () => $this->controller->listarActividadesControlador(1, 10, '', 'Manteni', $this->admin)
        );

        $this->assertSame(1, $porSegmento['data']['total']);
        $this->assertSame('Gatos', $porSegmento['data']['datos'][0]['producto']);
        $this->assertSame(1, $porProducto['data']['total']);
        $this->assertSame('Mantenimiento', $porProducto['data']['datos'][0]['producto']);
    }

    public function testGetUsuarioDevuelveSoloCamposPublicos(): void
    {
        $controller = new userController();
        $usuario = Usuario::create([
            'usuario_nombre' => 'Ana',
            'usuario_apellido' => 'Perez',
            'usuario_usuario' => 'ana123',
            'usuario_clave' => 'hash',
            'perfil' => 3,
        ]);

        [$status, $json] = $this->capture(
            fn () => $controller->getUsuario((string) $usuario->id)
        );

        $this->assertSame(200, $status);
        $this->assertSame(
            ['id', 'usuario_nombre', 'usuario_apellido', 'usuario_usuario'],
            array_keys($json['data'])
        );
    }

    public function testGetUsuarioValidaIdYExistencia(): void
    {
        $controller = new userController();

        [$statusInvalido, $jsonInvalido] = $this->capture(
            fn () => $controller->getUsuario('abc')
        );
        [$statusInexistente, $jsonInexistente] = $this->capture(
            fn () => $controller->getUsuario('999')
        );

        $this->assertSame(400, $statusInvalido);
        $this->assertSame('ID de usuario no válido', $jsonInvalido['data']);
        $this->assertSame(404, $statusInexistente);
        $this->assertSame('El usuario no existe', $jsonInexistente['data']);
    }

    private function crearActividad(array $overrides = []): Actividad
    {
        return Actividad::create(array_merge([
            'codigo_segmento' => 10,
            'segmento' => 'Material Vivo',
            'codigo_familia' => 1010,
            'familia' => 'Animales vivos',
            'codigo_clase' => 101015,
            'clase' => 'Animales de granja',
            'codigo_producto' => 10101501,
            'producto' => 'Gatos',
            'usuario_id' => 2,
        ], $overrides));
    }
}
