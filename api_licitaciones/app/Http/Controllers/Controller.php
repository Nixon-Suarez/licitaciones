<?php

namespace App\Http\Controllers;

class Controller
{
    protected function filtrarRegistrosVisibles($query, $usuario)
    {
        if ((int)$usuario->perfil === 3) {
            $query->where('usuario_id', $usuario->id);
        }

        return $query;
    }

    protected function puedeGestionarRegistro($usuario, $usuarioPropietarioId)
    {
        return (int)$usuario->perfil === 1
            || (int)$usuario->id === (int)$usuarioPropietarioId;
    }

    protected function responderAccesoDenegado()
    {
        http_response_code(403);
        echo json_encode([
            "code" => 403,
            "data" => "No tienes permisos para realizar esta acción"
        ]);
    }

    public function limpiarCadena($cadena)
    {
        $palabras = ["<script>", "</script>", "<script src>", "<script type=", "SELECT * FROM", "DELETE FROM", "INSERT INTO", "DROP TABLE", "DROP DATABASE", "TRUNCATE TABLA", "SHOW TABLES;", "SHOW DATABASE;", "<?php", "?>", "--", "^", "<", ">", "[", "]", "==", ";", "::"];
        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);
        foreach ($palabras as $palabra) {
            $cadena = str_ireplace($palabra, "", $cadena);
        }
        $cadena = trim($cadena);
        $cadena = stripslashes($cadena);
        $cadena = htmlspecialchars($cadena);
        return $cadena;
    }

    protected function verificarDatos($filtro, $cadena)
    {
        return !preg_match("/^$filtro$/", $cadena);
    }
}
