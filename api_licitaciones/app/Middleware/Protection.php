<?php

namespace App\Middleware;

use App\Models\Token;
use App\Models\Usuario;

class Protection
{
    public function verificarToken()
    {

        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $token = null;

        if (isset($headers['Authorization'])) {
            $token = trim(preg_replace('/^Bearer\s+/i', '', $headers['Authorization']));
        } elseif (isset($headers['authorization'])) {
            $token = trim(preg_replace('/^Bearer\s+/i', '', $headers['authorization']));
        } elseif (isset($_GET['Authorization'])) {
            $token = trim(preg_replace('/^Bearer\s+/i', '', $_GET['Authorization']));
        } elseif (isset($_GET['token'])) {
            $token = $_GET['token'];
        } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $token = trim(preg_replace('/^Bearer\s+/i', '', $_SERVER['HTTP_AUTHORIZATION']));
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $token = trim(preg_replace('/^Bearer\s+/i', '', $_SERVER['REDIRECT_HTTP_AUTHORIZATION']));
        }

        if (!$token) {
            http_response_code(401);
            echo json_encode(["error" => "Token requerido"]);
            exit;
        }

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

        $usuario = Usuario::find($tokensGuardado->user_id);
        if (!$usuario) {
            http_response_code(403);
            echo json_encode(["error" => "Usuario del token no válido"]);
            exit;
        }

        if (!in_array((int)$usuario->perfil, [1, 2, 3], true)) {
            http_response_code(403);
            echo json_encode(["error" => "Perfil sin permisos asignados"]);
            exit;
        }

        return $usuario;
    }

    public function requerirPerfil($usuario, array $perfilesPermitidos)
    {
        if (!in_array((int)$usuario->perfil, $perfilesPermitidos, true)) {
            http_response_code(403);
            echo json_encode(["error" => "No tienes permisos para realizar esta acción"]);
            exit;
        }
    }
}
