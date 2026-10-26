<?php

declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\TestCase;

/**
 * requerirPerfil() llama a exit cuando rechaza; por eso se ejecuta en un
 * proceso separado y se inspecciona su salida.
 */
final class ProtectionRequerirPerfilTest extends TestCase
{
    public function testPermitePerfilAutorizado(): void
    {
        $resultado = $this->ejecutarFixture('1', '1');

        $this->assertSame(0, $resultado['exit']);
        $this->assertSame('SIN_RESPUESTA', $resultado['output']);
    }

    public function testBloqueaPerfilNoAutorizadoConJsonY403(): void
    {
        $resultado = $this->ejecutarFixture('3', '1');

        $this->assertSame(0, $resultado['exit']);
        $this->assertStringContainsString('No tienes permisos', $resultado['output']);

        $json = json_decode($resultado['output'], true);
        $this->assertIsArray($json);
        $this->assertSame('No tienes permisos para realizar esta acción', $json['error']);
    }

    /**
     * @return array{exit: int, output: string}
     */
    private function ejecutarFixture(string $perfil, string $perfilesPermitidos): array
    {
        $script = dirname(__DIR__) . '/fixtures/requerir_perfil.php';
        $comando = escapeshellarg(PHP_BINARY)
            . ' ' . escapeshellarg($script)
            . ' ' . escapeshellarg($perfil)
            . ' ' . escapeshellarg($perfilesPermitidos);

        $salida = [];
        $codigo = 0;
        exec($comando . ' 2>&1', $salida, $codigo);

        return ['exit' => $codigo, 'output' => trim(implode("\n", $salida))];
    }
}
