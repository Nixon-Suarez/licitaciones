<?php
require_once "../../config/app.php";
require_once "../views/inc/session_start.php";
require_once "../../autoload.php";

use app\controllers\userController;
use app\controllers\searchController;
use app\controllers\OfertaController;
use app\controllers\ofertaDocumentController;

if (isset($_POST['modulo_usuario'])) {
    $insUsuario = new userController();
    if ($_POST['modulo_usuario'] == "registrar") {
        echo $insUsuario->registrarUsuarioControlador();
    }
} else {
    if (isset($_SESSION['id']) && $_SESSION['id'] != "") {
        if (isset($_POST['modulo_ofertas'])) {
            $insOferta = new OfertaController();
            if ($_POST['modulo_ofertas'] == "registrar_oferta") {
                echo $insOferta->crearOfertaControlador();
            }
            if ($_POST['modulo_ofertas'] == "detalle_oferta") {
                $id = (isset($_POST['oferta_id'])) ? $insLogin->limpiarCadena($_POST['oferta_id']) : "";
                echo json_encode($insOfertas->getOfertaControlador($id));
            }
            if ($_POST['modulo_ofertas'] == "actualizar_oferta") {
                echo $insOferta->ActualizarOfertaControlador();
            }
        }
        if (isset($_POST['modulo_buscador'])) {
            $insBuscador = new searchController();
            if ($_POST['modulo_buscador'] == "buscar_ofertas")
                echo $insBuscador->buscarDatosControlador("oferta");
            if ($_POST['modulo_buscador'] == "buscar_actividad")
                echo $insBuscador->buscarDatosControlador("actividad");
        }
        if (isset($_POST['modulo_ofertas_adjunto'])) {
            $insOfertaDoc = new ofertaDocumentController();
            if ($_POST['modulo_ofertas_adjunto'] == "registrar_adjunto") {
                echo $insOfertaDoc->crearOfertaDocumentControlador();
            }
            if ($_POST['modulo_ofertas_adjunto'] == "eliminar_adjunto") {
                echo $insOfertaDoc->eliminargetOfertaDocumentControlar();
            }
        }
    } else {
        session_destroy();
        header("Location: " . APP_URL . "?view=login/");
    }
}
