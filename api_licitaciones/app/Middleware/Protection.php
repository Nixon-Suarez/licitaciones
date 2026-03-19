<?php

namespace App\Middleware;

use App\Models\Token;

class Protection
{
    public function verificarToken()
    {

        $headers = getallheaders();

        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["error" => "Token requerido"]);
            exit;
        }

        $token = str_replace("Bearer ", "", $headers['Authorization']);
        $token_hash = hash('sha256', $token);

        $tokensGuardado = Token::select('user_id')
            ->where('token_hash', $token_hash)
            ->where('revoked', 0)
            ->where(function ($query) {
                $query->where('expires_at', '>', date('Y-m-d H:i:s'))
                    ->orWhereNull('expires_at');
            })
            ->first();

        if (!$tokensGuardado) {
            http_response_code(403);
            echo json_encode(["error" => "Token inválido"]);
            exit;
        }

        return $tokensGuardado;
    }
}
