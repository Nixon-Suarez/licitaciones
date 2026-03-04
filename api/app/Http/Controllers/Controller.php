<?php

namespace App\Http\Controllers;

abstract class Controller{
    public function limpiarCadena($cadena){
            $palabras = ["<script>", "</script>", "<script src>", "<script type=", "SELECT * FROM",  "DELETE FROM",  "INSERT INTO",  "DROP TABLE",  "DROP DATABASE", "TRUNCATE TABLA",   "SHOW TABLES;", "SHOW DATABASE;", "<?php", "?>", "--", "^", "<", ">", "[", "]", "==", ";", "::"];
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

    protected function verificarDatos($filtro, $cadena){
        return !preg_match("/^$filtro$/", $cadena);
    }
}
