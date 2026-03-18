<?php

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
$uri = explode('?', $_SERVER['REQUEST_URI'])[0];

$auth = new Protection();

/*
|--------------------------------------------------------------------------
| Rutas
|--------------------------------------------------------------------------
*/

if(preg_match('/\/api\/actividad\/find\/(\d+)/', $uri, $matches) && $method == "GET") {

    $auth->verificarToken(); 
    $id_actividad = $matches[1];

    $controller = new ActividadesController();
    $controller->getActividades($id_actividad);
    exit;
}

if (preg_match('/\/api\/actividad\/list\/(\d+)\/(\d+)\/(\d+)\/(\d+)/', $uri, $matches) && $method == "GET") {

    $auth->verificarToken();

    $pagina = $matches[1];
    $registros = $matches[2];
    $segmento = $matches[3];
    $producto = $matches[4];

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

    $auth->verificarToken();

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

if(preg_match('/\/api\/ofertaDocumento\/find\/(\d+)/', $uri, $matches) && $method == "GET") {

    $auth->verificarToken();
    $id_documento = $matches[1];

    $controller = new ofertaDocumentController();
    $controller->getOfertaDocumentControlar($id_documento);
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

if(preg_match('/\/api\/oferta\/find\/(\d+)/', $uri, $matches) && $method == "GET") {

    $auth->verificarToken();
    $id_oferta = $matches[1];

    $controller = new ofertaController();
    $controller->getOfertaControlador($id_oferta);
    exit;
}

if (preg_match('/\/api\/oferta\/list\/(\d+)\/(\d+)\/(\d+)\/(\d+)/', $uri, $matches) && $method == "GET") {

    $auth->verificarToken();

    $pagina = $matches[1];
    $registros = $matches[2];
    $segmento = $matches[3];
    $producto = $matches[4];

    $controller = new ofertaController();
    $controller->listarOfertaControlador($pagina, $registros, $segmento, $producto);
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
    "code"=>404,
    "data"=>"Ruta no encontrada"
]);