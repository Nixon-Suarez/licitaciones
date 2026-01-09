<?php

namespace app\controllers;

use App\Models\CategoriaGasto;
use app\models\mainModel;

class searchController extends mainModel
{
    public function modulosBusquedaControlador($modulo)
    {
        $listaModulos = [
            'ofertasList',
            'actividadList'
        ];
        if (in_array($modulo, $listaModulos)) {
            return true;
        } else {
            return false;
        }
    }
    public function buscarDatosControlador($tipo_busqueda)
    {
        $url = trim($this->limpiarCadena($_POST['modulo_url'] ?? ''));
        if (!$this->modulosBusquedaControlador($url)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrio un error inesperado",
                "texto" => "No podemos procesar su busqueda, por favor intente nuevamente",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        #Verificando integridad de los datos - solo validar si el tipo es oferta
        if ($tipo_busqueda == "oferta") {
            $texto = trim($this->limpiarCadena($_POST['txt_buscador'] ?? ''));
        } elseif ($tipo_busqueda == "actividad") {
            $texto = trim($this->limpiarCadena($_POST['txt_segmento'] ?? ''));
        }
        if (!empty($texto)) {
            if ($this->verificarDatos("^(?!\s*$)[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s]{1,400}$", $texto)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrio un error inesperado",
                    "texto" => "La descripcion de la oferta no coincide con el formato solicitado",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
        }
        if ($tipo_busqueda == "oferta") {
            $texto = trim($this->limpiarCadena($_POST['txt_buscador'] ?? ''));
            $concecutivo = trim($this->limpiarCadena($_POST['txt_consecutivo'] ?? ''));
            $_SESSION[$url] = [
                "texto" => $texto,
                "consecutivo" => $concecutivo
            ];

            $alerta = [
                "tipo" => "redireccionar",
                "titulo" => "Busqueda realizada",
                "texto" => "Se ha realizado la busqueda correctamente",
                "icono" => "success",
                "url" => APP_URL . "?view=" . $url . "/1/"
            ];
            return json_encode($alerta);
        }
        if ($tipo_busqueda == "actividad") {
            $segmento = trim($this->limpiarCadena($_POST['txt_segmento'] ?? ''));
            $producto = trim($this->limpiarCadena($_POST['txt_producto'] ?? ''));
            $_SESSION[$url] = [
                "segmento" => $segmento,
                "producto" => $producto
            ];

            $alerta = [
                "tipo" => "redireccionar",
                "titulo" => "Busqueda realizada",
                "texto" => "Se ha realizado la busqueda correctamente",
                "icono" => "success",
                "url" => APP_URL . "?view=" . $url . "/1/"
            ];
            return json_encode($alerta);
        }
    }
}
