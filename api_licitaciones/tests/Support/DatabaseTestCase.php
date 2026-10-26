<?php

declare(strict_types=1);

namespace Tests\Support;

use Illuminate\Database\Capsule\Manager as Capsule;
use PHPUnit\Framework\TestCase;

/**
 * Levanta una base SQLite en memoria con el esquema que usan los modelos.
 * No toca MySQL ni la configuración real de la aplicación.
 */
abstract class DatabaseTestCase extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        http_response_code(200);

        $capsule = new Capsule();
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $this->createSchema();
    }

    /**
     * Ejecuta una acción que imprime JSON y devuelve [status, json, raw].
     *
     * @return array{0: int|false, 1: mixed, 2: string}
     */
    protected function capture(callable $action): array
    {
        ob_start();
        try {
            $action();
        } finally {
            $raw = (string) ob_get_clean();
        }

        return [http_response_code(), json_decode($raw, true), $raw];
    }

    private function createSchema(): void
    {
        $schema = Capsule::schema();

        $schema->create('usuario', function ($table) {
            $table->increments('id');
            $table->string('usuario_nombre');
            $table->string('usuario_apellido')->nullable();
            $table->string('usuario_usuario');
            $table->string('usuario_clave');
            $table->string('clave_no_cript')->nullable();
            $table->integer('perfil')->default(3);
            $table->timestamp('usuario_creado')->nullable();
            $table->timestamp('usuario_actualizado')->nullable();
        });

        $schema->create('api_tokens', function ($table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('token')->nullable();
            $table->string('token_hash');
            $table->timestamp('expires_at')->nullable();
            $table->boolean('revoked')->default(false);
            $table->timestamp('created_at')->nullable();
        });

        $schema->create('actividades', function ($table) {
            $table->increments('id');
            $table->integer('codigo_segmento');
            $table->string('segmento');
            $table->integer('codigo_familia');
            $table->string('familia');
            $table->integer('codigo_clase');
            $table->string('clase');
            $table->integer('codigo_producto');
            $table->string('producto');
            $table->integer('usuario_id');
        });

        $schema->create('ofertas', function ($table) {
            $table->increments('id');
            $table->string('consecutivo');
            $table->string('objeto');
            $table->string('descripcion');
            $table->string('moneda');
            $table->decimal('presupuesto', 15, 2);
            $table->integer('actividad_id');
            $table->date('fecha_inicio');
            $table->string('hora_inicio');
            $table->date('fecha_cierre');
            $table->string('hora_cierre');
            $table->string('estado');
            $table->integer('usuario_id');
            $table->timestamp('creado_en')->nullable();
            $table->timestamp('actualizado_en')->nullable();
        });

        $schema->create('ofertas_documentos', function ($table) {
            $table->increments('id');
            $table->integer('licitacion_id');
            $table->string('titulo');
            $table->string('descripcion');
            $table->string('archivo');
            $table->string('nombre_original');
            $table->string('ruta_archivo');
            $table->timestamp('creado_en')->nullable();
        });
    }
}
