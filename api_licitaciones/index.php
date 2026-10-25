<?php
require_once "./config/app.php";
require_once __DIR__ . "/vendor/autoload.php";
require_once "./config/database.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, Accept");
header("Access-Control-Expose-Headers: Content-Disposition");

use App\Middleware\Protection;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\ofertaDocumentController;
use App\Http\Controllers\ofertaController;
use App\Http\Controllers\userController;
use App\Http\Controllers\Controller;


$method = $_SERVER['REQUEST_METHOD'];
$requestUri = $_SERVER['REQUEST_URI'];
$basePath = '/php/index.php/licitaciones/api_licitaciones';

if (strpos($requestUri, $basePath) === 0) {
    $uri = substr($requestUri, strlen($basePath));
} else {
    $uri = $requestUri;
}

$uri = explode('?', $uri)[0];

$auth = new Protection();

$ContladorBase = new Controller(); // Inicializa el controlador base para manejar excepciones y errores 

// Manejo de peticiones OPTIONS (CORS preflight)
if ($method == "OPTIONS") {
    http_response_code(200);
    exit;
}

// Debug: Log de la petición
// error_log("Method: $method, URI: $uri");


// Rutas

if ($uri == "/" && $method == "GET") {
    echo json_encode(["message" => "API Licitaciones"]);
    exit;
}

if ($uri == '/api/actividad/get' && $method == "GET") {

    $usuario = $auth->verificarToken();

    $id_actividad = isset($_GET['id']) ? $_GET['id'] : null;
    $id_actividad = $ContladorBase->limpiarCadena($id_actividad);

    $controller = new ActividadesController();
    $controller->getActividades($id_actividad, $usuario);
    exit;
}

if ($uri == "/api/actividad/update" && $method == "PUT") {

    $usuario = $auth->verificarToken();

    $controller = new ActividadesController();
    $controller->ActualizarActividadControlador($usuario);
    exit;
}

if ($uri == '/api/actividad/list' && $method == "GET") {

    $usuario = $auth->verificarToken();

    $pagina = $ContladorBase->limpiarCadena($_GET['pagina']) ?? 1;
    $registros = $ContladorBase->limpiarCadena($_GET['registros']) ?? 10;
    $segmento = $ContladorBase->limpiarCadena($_GET['segmento']) ?? '';
    $producto = $ContladorBase->limpiarCadena($_GET['producto']) ?? '';

    $controller = new ActividadesController();
    $controller->listarActividadesControlador($pagina, $registros, $segmento, $producto, $usuario);
    exit;
}

if ($uri == "/api/actividad/insert" && $method == "POST") {

    $usuario = $auth->verificarToken();

    $controller = new ActividadesController();
    $controller->InsertActividadControlador($usuario);
    exit;
}

if ($uri == "/api/actividad/masivo" && $method == "POST") {

    $usuario = $auth->verificarToken();

    $controller = new ActividadesController();
    $controller->crearActividadMasivoControlador($usuario);
    exit;
}

if ($uri == "/api/actividad/plantilla" && $method == "GET") {

    $auth->verificarToken();

    $controller = new ActividadesController();
    $controller->descargarPlantillaControlador();
    exit;
}

if (preg_match('/^\/api\/actividad\/delete\/(\d+)$/', $uri, $matches) && $method == "DELETE") {

    $usuario = $auth->verificarToken();
    $id_actividad = $matches[1];
    $id_actividad = $ContladorBase->limpiarCadena($id_actividad);

    $controller = new ActividadesController();
    $controller->eliminarActividadControlador($id_actividad, $usuario);
    exit;
}

if ($uri == "/api/user/login" && $method == "POST") {
    $controller = new loginController();
    $controller->iniciarSesionControlador();
    exit;
}

if ($uri == "/api/user/insert" && $method == "POST") {

    $controller = new userController();
    $controller->registrarUsuarioControlador();
    exit;
}

if (preg_match('/^\/api\/user\/delete\/(\d+)$/', $uri, $matches) && $method == "DELETE") {

    $usuario = $auth->verificarToken();
    $auth->requerirPerfil($usuario, [1]);
    $id_user = $matches[1];
    $id_user = $ContladorBase->limpiarCadena($id_user);

    $controller = new userController();
    $controller->eliminarUsuarioControlador($id_user);
    exit;
}

if ($uri == "/api/user/update" && $method == "PUT") {

    $usuario = $auth->verificarToken();
    $auth->requerirPerfil($usuario, [1]);

    $controller = new userController();
    $controller->actualizarUsuarioControlador();
    exit;
}

if ($uri == "/api/ofertaDocumento/insert" && $method == "POST") {

    $usuario = $auth->verificarToken();

    $controller = new ofertaDocumentController();
    $controller->crearOfertaDocumentControlador($usuario);
    exit;
}

if ($uri == '/api/ofertaDocumento/get' && $method == "GET") {

    $usuario = $auth->verificarToken();
    $id_documento = $_GET['id'] ?? null;
    $id_documento = $ContladorBase->limpiarCadena($id_documento);

    $controller = new ofertaDocumentController();
    $controller->getOfertaDocumentControlador($id_documento, $usuario);
    exit;
}

if (preg_match('/^\/api\/ofertaDocumento\/delete\/(\d+)$/', $uri, $matches) && $method == "DELETE") {

    $usuario = $auth->verificarToken();
    $id_documento = $matches[1];
    $id_documento = $ContladorBase->limpiarCadena($id_documento);

    $controller = new ofertaDocumentController();
    $controller->eliminarOfertaDocumentControlador($id_documento, $usuario);
    exit;
}

if ($uri == "/api/oferta/insert" && $method == "POST") {

    $usuario = $auth->verificarToken();

    $controller = new ofertaController();
    $controller->InsertOfertaControlador($usuario);
    exit;
}

if (preg_match('/\/api\/oferta\/find\/(\d+)/', $uri, $matches) && $method == "GET") {

    $usuario = $auth->verificarToken();
    $id_oferta = $matches[1];
    $id_oferta = $ContladorBase->limpiarCadena($id_oferta);

    $controller = new ofertaController();
    $controller->getOfertaControlador($id_oferta, $usuario);
    exit;
}

if ($uri == '/api/oferta/list' && $method == "GET") {

    $usuario = $auth->verificarToken();

    $pagina = $ContladorBase->limpiarCadena($_GET['pagina']) ?? 1;
    $registros = $ContladorBase->limpiarCadena($_GET['registros']) ?? 10;
    $descripcion = $ContladorBase->limpiarCadena($_GET['descripcion']) ?? '';
    $consecutivo = $ContladorBase->limpiarCadena($_GET['consecutivo']) ?? '';
    $objeto = $ContladorBase->limpiarCadena($_GET['objeto']) ?? '';

    $controller = new ofertaController();
    $controller->listarOfertaControlador($pagina, $registros, $descripcion, $consecutivo, $usuario, $objeto);
    exit;
}

if ($uri == "/api/oferta/update" && $method == "PUT") {

    $usuario = $auth->verificarToken();

    $controller = new ofertaController();
    $controller->ActualizarOfertaControlador($usuario);
    exit;
}

if (preg_match('/^\/api\/oferta\/delete\/(\d+)$/', $uri, $matches) && $method == "DELETE") {

    $usuario = $auth->verificarToken();
    $id_oferta = $ContladorBase->limpiarCadena($matches[1]);

    $controller = new ofertaController();
    $controller->eliminarOfertaControlador($id_oferta, $usuario);
    exit;
}

if ($uri == "/api/oferta/excel" && $method == "GET") {

    $usuario = $auth->verificarToken();

    $pagina = $ContladorBase->limpiarCadena($_GET['pagina'] ?? 1);
    $registros = $ContladorBase->limpiarCadena($_GET['registros'] ?? 10);
    $descripcion = $ContladorBase->limpiarCadena($_GET['descripcion'] ?? '');
    $consecutivo = $ContladorBase->limpiarCadena($_GET['consecutivo'] ?? '');
    $objeto = $ContladorBase->limpiarCadena($_GET['objeto'] ?? '');

    $controller = new ofertaController();
    $controller->generarExcelOfertaControlador($pagina, $registros, $descripcion, $consecutivo, $usuario, $objeto);
    exit;
}


http_response_code(404);
echo json_encode([
    "code" => 404,
    "data" => "Ruta no encontrada"
]);
