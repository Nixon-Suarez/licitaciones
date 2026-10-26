<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Http\Controllers\ofertaController;
use App\Models\Oferta;
use Tests\Support\DatabaseTestCase;

final class ConsecutivoOfertaTest extends DatabaseTestCase
{
    private ofertaController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new ofertaController();
    }

    public function testGeneraPrimerConsecutivoCuandoNoHayOfertas(): void
    {
        $this->assertSame('PO-001-' . date('y'), $this->controller->crearConsecutivoOfertaControlador());
    }

    public function testIncrementaElUltimoConsecutivoDelAnioActual(): void
    {
        $anio = date('y');
        $this->insertarOferta('PO-001-' . $anio);

        $this->assertSame('PO-002-' . $anio, $this->controller->crearConsecutivoOfertaControlador());
    }

    public function testTomaElUltimoRegistroPorIdYNoPorValorDelConsecutivo(): void
    {
        $anio = date('y');
        $this->insertarOferta('PO-010-' . $anio);
        $this->insertarOferta('PO-002-' . $anio);

        $this->assertSame('PO-003-' . $anio, $this->controller->crearConsecutivoOfertaControlador());
    }

    public function testIgnoraConsecutivosDeOtrosAnios(): void
    {
        $otroAnio = date('y') === '99' ? '98' : '99';
        $this->insertarOferta('PO-099-' . $otroAnio);

        $this->assertSame('PO-001-' . date('y'), $this->controller->crearConsecutivoOfertaControlador());
    }

    private function insertarOferta(string $consecutivo): void
    {
        Oferta::create([
            'consecutivo' => $consecutivo,
            'objeto' => 'Objeto de prueba',
            'descripcion' => 'Descripcion de prueba',
            'moneda' => 'COP',
            'presupuesto' => 1000,
            'actividad_id' => 1,
            'fecha_inicio' => '2026-01-01',
            'hora_inicio' => '08:00',
            'fecha_cierre' => '2026-01-02',
            'hora_cierre' => '18:00',
            'estado' => 'creacion',
            'usuario_id' => 1,
        ]);
    }
}
