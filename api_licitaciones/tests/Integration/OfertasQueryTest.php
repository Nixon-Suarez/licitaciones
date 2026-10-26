<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Http\Controllers\ofertaController;
use App\Http\Controllers\ofertaDocumentController;
use App\Models\Oferta;
use App\Models\OfertaDocumento;
use Tests\Support\DatabaseTestCase;

final class OfertasQueryTest extends DatabaseTestCase
{
    private ofertaController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ofertaController();
    }

    public function testGetOfertaDevuelveDatosFormateados(): void
    {
        $oferta = $this->crearOferta(2);

        [$status, $json] = $this->capture(
            fn () => $this->controller->getOfertaControlador(
                (string) $oferta->id,
                (object) ['id' => 2, 'perfil' => 3]
            )
        );

        $this->assertSame(200, $status);
        $this->assertSame(200, $json['code']);
        $this->assertSame('PO-001-26', $json['data']['consecutivo']);
        $this->assertSame('2026-03-01', $json['data']['fecha_inicio']);
        $this->assertSame('2026-03-15', $json['data']['fecha_cierre']);
        $this->assertSame(1500000.5, (float) $json['data']['presupuesto']);
    }

    public function testGetOfertaValidaIdExistenciaYPermisos(): void
    {
        $oferta = $this->crearOferta(2);

        [$statusInvalido, $jsonInvalido] = $this->capture(
            fn () => $this->controller->getOfertaControlador('abc', $this->admin())
        );
        [$statusInexistente] = $this->capture(
            fn () => $this->controller->getOfertaControlador('999', $this->admin())
        );
        [$statusAjeno, $jsonAjeno] = $this->capture(
            fn () => $this->controller->getOfertaControlador(
                (string) $oferta->id,
                (object) ['id' => 77, 'perfil' => 3]
            )
        );

        $this->assertSame(400, $statusInvalido);
        $this->assertSame('ID de oferta no válido', $jsonInvalido['data']);
        $this->assertSame(400, $statusInexistente);
        $this->assertSame(400, $statusAjeno);
        $this->assertSame('La oferta no existe', $jsonAjeno['data']);
    }

    public function testListarOfertasPaginaYFiltraPorConsecutivo(): void
    {
        $this->crearOferta(2, ['consecutivo' => 'PO-001-26']);
        $this->crearOferta(2, ['consecutivo' => 'PO-002-26']);
        $this->crearOferta(2, ['consecutivo' => 'PO-003-26']);

        [$status, $json] = $this->capture(
            fn () => $this->controller->listarOfertaControlador(1, 2, '', '', $this->admin())
        );
        [, $filtrado] = $this->capture(
            fn () => $this->controller->listarOfertaControlador(1, 10, '', '002', $this->admin())
        );

        $this->assertSame(200, $status);
        $this->assertSame(3, $json['data']['total']);
        $this->assertSame(2, $json['data']['paginas']);
        $this->assertCount(2, $json['data']['datos']);
        $this->assertSame(1, $filtrado['data']['total']);
        $this->assertSame('PO-002-26', $filtrado['data']['datos'][0]['consecutivo']);
    }

    public function testListarOfertasFiltraPorDescripcionYRespetaPerfil3(): void
    {
        $this->crearOferta(2, ['descripcion' => 'Mantenimiento preventivo']);
        $this->crearOferta(3, ['descripcion' => 'Compra de equipos']);

        [, $porDescripcion] = $this->capture(
            fn () => $this->controller->listarOfertaControlador(1, 10, 'Manteni', '', $this->admin())
        );
        [, $propias] = $this->capture(
            fn () => $this->controller->listarOfertaControlador(
                1,
                10,
                '',
                '',
                (object) ['id' => 2, 'perfil' => 3]
            )
        );

        $this->assertSame(1, $porDescripcion['data']['total']);
        $this->assertSame(1, $propias['data']['total']);
        $this->assertSame('Mantenimiento preventivo', $propias['data']['datos'][0]['descripcion']);
    }

    public function testGetOfertaDocumentosConstruyeUrlYDevuelve204SinDocumentos(): void
    {
        $oferta = $this->crearOferta(2);
        $documentos = new ofertaDocumentController();

        [$statusVacio, $jsonVacio] = $this->capture(
            fn () => $documentos->getOfertaDocumentControlador((string) $oferta->id, $this->admin())
        );

        OfertaDocumento::create([
            'licitacion_id' => $oferta->id,
            'titulo' => 'Pliego',
            'descripcion' => 'Documento principal',
            'archivo' => 'pliego.pdf',
            'nombre_original' => 'Pliego.pdf',
            'ruta_archivo' => 'documentos_ofertas/',
        ]);

        [$status, $json] = $this->capture(
            fn () => $documentos->getOfertaDocumentControlador((string) $oferta->id, $this->admin())
        );

        $this->assertSame(204, $statusVacio);
        $this->assertSame(204, $jsonVacio['code']);
        $this->assertSame(200, $status);
        $this->assertSame('public/documentos_ofertas/pliego.pdf', $json['data'][0]['url_descarga']);
    }

    public function testGetOfertaDocumentosUsaRutaPorDefectoCuandoLaGuardadaNoEsValida(): void
    {
        $oferta = $this->crearOferta(2);
        $documentos = new ofertaDocumentController();
        OfertaDocumento::create([
            'licitacion_id' => $oferta->id,
            'titulo' => 'Pliego',
            'descripcion' => 'Documento principal',
            'archivo' => 'pliego.pdf',
            'nombre_original' => 'Pliego.pdf',
            'ruta_archivo' => 'otra_ruta/',
        ]);

        [, $json] = $this->capture(
            fn () => $documentos->getOfertaDocumentControlador((string) $oferta->id, $this->admin())
        );

        $this->assertSame('public/documentos_ofertas/pliego.pdf', $json['data'][0]['url_descarga']);
    }

    public function testEliminarOfertaBorraRegistrosYArchivos(): void
    {
        $oferta = $this->crearOferta(2);
        $directorio = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'licitaciones_test_' . uniqid();
        mkdir($directorio, 0777, true);
        $rutaArchivo = $directorio . DIRECTORY_SEPARATOR . 'adjunto.pdf';
        file_put_contents($rutaArchivo, 'contenido');

        OfertaDocumento::create([
            'licitacion_id' => $oferta->id,
            'titulo' => 'Adjunto',
            'descripcion' => 'Documento adjunto',
            'archivo' => 'adjunto.pdf',
            'nombre_original' => 'adjunto.pdf',
            'ruta_archivo' => $directorio,
        ]);

        [$status, $json] = $this->capture(
            fn () => $this->controller->eliminarOfertaControlador((string) $oferta->id, $this->admin())
        );

        $this->assertSame(200, $status);
        $this->assertSame(200, $json['code']);
        $this->assertNull(Oferta::find($oferta->id));
        $this->assertSame(0, OfertaDocumento::where('licitacion_id', $oferta->id)->count());
        $this->assertFileDoesNotExist($rutaArchivo);

        @rmdir($directorio);
    }

    public function testEliminarOfertaValidaIdExistenciaYPermisos(): void
    {
        $oferta = $this->crearOferta(2);

        [$statusInvalido, $jsonInvalido] = $this->capture(
            fn () => $this->controller->eliminarOfertaControlador('abc', $this->admin())
        );
        [$statusInexistente] = $this->capture(
            fn () => $this->controller->eliminarOfertaControlador('999', $this->admin())
        );
        [$statusAjeno, $jsonAjeno] = $this->capture(
            fn () => $this->controller->eliminarOfertaControlador(
                (string) $oferta->id,
                (object) ['id' => 77, 'perfil' => 3]
            )
        );

        $this->assertSame(400, $statusInvalido);
        $this->assertSame('ID de oferta no válido', $jsonInvalido['data']);
        $this->assertSame(404, $statusInexistente);
        $this->assertSame(403, $statusAjeno);
        $this->assertSame('No tienes permisos para realizar esta acción', $jsonAjeno['data']);
        $this->assertNotNull(Oferta::find($oferta->id));
    }

    private function admin(): object
    {
        return (object) ['id' => 1, 'perfil' => 1];
    }

    private function crearOferta(int $usuarioId, array $overrides = []): Oferta
    {
        return Oferta::create(array_merge([
            'consecutivo' => 'PO-001-26',
            'objeto' => 'Compra de equipos',
            'descripcion' => 'Descripcion de la oferta',
            'moneda' => 'COP',
            'presupuesto' => 1500000.50,
            'actividad_id' => 1,
            'fecha_inicio' => '2026-03-01',
            'hora_inicio' => '08:00',
            'fecha_cierre' => '2026-03-15',
            'hora_cierre' => '18:00',
            'estado' => 'creacion',
            'usuario_id' => $usuarioId,
            'creado_en' => '2026-02-01 10:00:00',
            'actualizado_en' => '2026-02-01 10:00:00',
        ], $overrides));
    }
}
