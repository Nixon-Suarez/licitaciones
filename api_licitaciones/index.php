<?php
require_once "./config/app.php";
require_once __DIR__ . "/vendor/autoload.php";
require_once "./config/database.php";

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

use App\Middleware\Protection;
use App\Http\Controllers\ActividadesController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\ofertaDocumentController;
use App\Http\Controllers\ofertaController;
use App\Http\Controllers\userController;

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


// Manejo de peticiones OPTIONS (CORS preflight)
if ($method == "OPTIONS") {
    http_response_code(200);
    exit;
}

// Debug: Log de la petición
error_log("Method: $method, URI: $uri");


// Rutas

if ($uri == "/" && $method == "GET") {
    echo json_encode(["message" => "API Licitaciones"]);
    exit;
}

if ($uri == '/api/actividad/get' && $method == "GET") {

    $auth->verificarToken();

    $id_actividad = isset($_GET['id']) ? $_GET['id'] : null;

    $controller = new ActividadesController();
    $controller->getActividades($id_actividad);
    exit;
}

if ($uri == '/api/actividad/list' && $method == "GET") {

    $auth->verificarToken();

    $pagina = $_GET['pagina'] ?? 1;
    $registros = $_GET['registros'] ?? 10;
    $segmento = $_GET['segmento'] ?? '';
    $producto = $_GET['producto'] ?? '';

    $controller = new ActividadesController();
    $controller->listarActividadesControlador($pagina, $registros, $segmento, $producto);
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

    $auth->verificarToken();
    $id_user = $matches[1];

    $controller = new userController();
    $controller->eliminarUsuarioControlador($id_user);
    exit;
}

if ($uri == "/api/user/update" && $method == "PUT") {

    $auth->verificarToken();

    $controller = new userController();
    $controller->actualizarUsuarioControlador();
    exit;
}

if ($uri == "/api/ofertaDocumento/insert" && $method == "POST") {

    $auth->verificarToken();

    $controller = new ofertaDocumentController();
    $controller->crearOfertaDocumentControlador();
    exit;
}

if ($uri == '/api/ofertaDocumento/get' && $method == "GET") {

    $auth->verificarToken();
    $id_documento = $_GET['id'] ?? null;

    $controller = new ofertaDocumentController();
    $controller->getOfertaDocumentControlador($id_documento);
    exit;
}

if (preg_match('/^\/api\/ofertaDocumento\/delete\/(\d+)$/', $uri, $matches) && $method == "DELETE") {

    $auth->verificarToken();
    $id_documento = $matches[1];

    $controller = new ofertaDocumentController();
    $controller->eliminarOfertaDocumentControlador($id_documento);
    exit;
}

if ($uri == "/api/oferta/insert" && $method == "POST") {

    $auth->verificarToken();

    $controller = new ofertaController();
    $controller->InsertOfertaControlador();
    exit;
}

if (preg_match('/\/api\/oferta\/find\/(\d+)/', $uri, $matches) && $method == "GET") {

    $auth->verificarToken();
    $id_oferta = $matches[1];

    $controller = new ofertaController();
    $controller->getOfertaControlador($id_oferta);
    exit;
}

if ($uri == '/api/oferta/list' && $method == "GET") {

    $auth->verificarToken();

    $pagina = $_GET['pagina'] ?? 1;
    $registros = $_GET['registros'] ?? 10;
    $descripcion = $_GET['descripcion'] ?? '';
    $consecutivo = $_GET['consecutivo'] ?? '';

    $controller = new ofertaController();
    $controller->listarOfertaControlador($pagina, $registros, $descripcion, $consecutivo);
    exit;
}

if ($uri == "/api/oferta/update" && $method == "PUT") {

    $auth->verificarToken();

    $controller = new ofertaController();
    $controller->ActualizarOfertaControlador();
    exit;
}


http_response_code(404);
echo json_encode([
    "code" => 404,
    "data" => "Ruta no encontrada"
]);
