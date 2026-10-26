<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\Token;
use Tests\Support\DatabaseTestCase;

final class TokenLogicTest extends DatabaseTestCase
{
    public function testTokenSinExpiracionNiRevocadoEsValido(): void
    {
        $token = Token::create([
            'user_id' => 1,
            'token_hash' => hash('sha256', 'token-sin-fecha'),
            'expires_at' => null,
            'revoked' => false,
        ]);

        $this->assertFalse($token->isExpired());
        $this->assertTrue($token->isValid());
    }

    public function testTokenConFechaFuturaEsValido(): void
    {
        $token = Token::create([
            'user_id' => 1,
            'token_hash' => hash('sha256', 'token-futuro'),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 day')),
            'revoked' => false,
        ]);

        $this->assertFalse($token->isExpired());
        $this->assertTrue($token->isValid());
    }

    public function testTokenConFechaPasadaEstaExpiradoYNoEsValido(): void
    {
        $token = Token::create([
            'user_id' => 1,
            'token_hash' => hash('sha256', 'token-vencido'),
            'expires_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
            'revoked' => false,
        ]);

        $this->assertTrue($token->isExpired());
        $this->assertFalse($token->isValid());
    }

    public function testTokenRevocadoNoEsValido(): void
    {
        $token = Token::create([
            'user_id' => 1,
            'token_hash' => hash('sha256', 'token-revocado'),
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 day')),
            'revoked' => true,
        ]);

        $this->assertFalse($token->isExpired());
        $this->assertFalse($token->isValid());
    }

    public function testScopeActiveExcluyeTokensRevocados(): void
    {
        Token::create(['user_id' => 1, 'token_hash' => 'activo', 'revoked' => false]);
        Token::create(['user_id' => 1, 'token_hash' => 'revocado', 'revoked' => true]);

        $this->assertSame(1, Token::active()->count());
    }

    public function testScopeNotExpiredExcluyeVencidosYConservaSinExpiracion(): void
    {
        Token::create(['user_id' => 1, 'token_hash' => 'futuro', 'expires_at' => date('Y-m-d H:i:s', strtotime('+1 day'))]);
        Token::create(['user_id' => 1, 'token_hash' => 'pasado', 'expires_at' => date('Y-m-d H:i:s', strtotime('-1 day'))]);
        Token::create(['user_id' => 1, 'token_hash' => 'sin-fecha', 'expires_at' => null]);

        $this->assertSame(2, Token::notExpired()->count());
    }
}
