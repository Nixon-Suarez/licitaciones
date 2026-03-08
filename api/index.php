<?php

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// require_once "controllers/CourseController.php";
// require_once "middleware/Protection.php";

// use App\Middleware\Protection;

// $method = $_SERVER['REQUEST_METHOD'];
// $uri = explode('?', $_SERVER['REQUEST_URI'])[0];

// $auth = new Protection();

// /*
// |--------------------------------------------------------------------------
// | Rutas protegidas
// |--------------------------------------------------------------------------
// */

// if ($uri == "/api/curso" && $method == "POST") {

//     $auth->verificarToken();

//     $controller = new CourseController();
//     $controller->insert();
//     exit;
// }

// if ($uri == "/api/curso" && $method == "GET") {

//     $auth->verificarToken();

//     $controller = new CourseController();
//     $controller->getAll();
//     exit;
// }

// http_response_code(404);
// echo json_encode([
//     "code"=>404,
//     "data"=>"Ruta no encontrada"
// ]);