<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Actividad;
use App\Models\Oferta;
use App\Models\OfertaDocumento;
use App\Models\Token;
use App\Models\Usuario;
use Tests\Support\DatabaseTestCase;

final class ModelMetadataTest extends DatabaseTestCase
{
    public function testUsuarioConfiguraTablaAutenticacionYCamposOcultos(): void
    {
        $model = new Usuario();
        $model->usuario_clave = 'hash-secreto';

        $this->assertSame('usuario', $model->getTable());
        $this->assertSame('usuario_usuario', $model->getAuthIdentifierName());
        $this->assertSame('hash-secreto', $model->getAuthPassword());
        $this->assertContains('usuario_clave', $model->getHidden());
        $this->assertContains('usuario_usuario', $model->getFillable());
    }

    public function testTokenConfiguraTablaCastsYOcultaElHash(): void
    {
        $model = new Token();

        $this->assertSame('api_tokens', $model->getTable());
        $this->assertSame('created_at', Token::CREATED_AT);
        $this->assertNull(Token::UPDATED_AT);
        $this->assertContains('token_hash', $model->getFillable());
        $this->assertContains('token_hash', $model->getHidden());
        $this->assertSame('boolean', $model->getCasts()['revoked']);
        $this->assertSame('datetime', $model->getCasts()['expires_at']);
        $this->assertInstanceOf(Usuario::class, $model->user()->getRelated());
        $this->assertSame('user_id', $model->user()->getForeignKeyName());
    }

    public function testActividadConfiguraRelacionConOfertasYSinTimestamps(): void
    {
        $model = new Actividad();

        $this->assertSame('actividades', $model->getTable());
        $this->assertFalse($model->usesTimestamps());
        $this->assertContains('codigo_producto', $model->getFillable());
        $this->assertInstanceOf(Oferta::class, $model->ofertas()->getRelated());
        $this->assertSame('actividad_id', $model->ofertas()->getForeignKeyName());
    }

    public function testOfertaConfiguraCastDecimalYRelaciones(): void
    {
        $model = new Oferta();

        $this->assertSame('ofertas', $model->getTable());
        $this->assertSame('creado_en', Oferta::CREATED_AT);
        $this->assertSame('actualizado_en', Oferta::UPDATED_AT);
        $this->assertSame('decimal:2', $model->getCasts()['presupuesto']);
        $this->assertInstanceOf(Actividad::class, $model->actividad()->getRelated());
        $this->assertInstanceOf(OfertaDocumento::class, $model->documentos()->getRelated());
        $this->assertSame('licitacion_id', $model->documentos()->getForeignKeyName());
    }

    public function testOfertaDocumentoConfiguraTablaYRelacion(): void
    {
        $model = new OfertaDocumento();

        $this->assertSame('ofertas_documentos', $model->getTable());
        $this->assertSame('creado_en', OfertaDocumento::CREATED_AT);
        $this->assertNull(OfertaDocumento::UPDATED_AT);
        $this->assertContains('ruta_archivo', $model->getFillable());
        $this->assertInstanceOf(Oferta::class, $model->oferta()->getRelated());
        $this->assertSame('licitacion_id', $model->oferta()->getForeignKeyName());
    }
}
