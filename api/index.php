<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once "controllers/CourseController.php";

$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Quitar parámetros si existen
$uri = explode('?', $uri)[0];

// // Ejemplo: /api/curso
// if ($uri == "/api/curso" && $method == "POST") {
//     $controller = new CourseController();
//     $controller->insert();
//     exit;
// }

// if ($uri == "/api/curso" && $method == "GET") {
//     $controller = new CourseController();
//     $controller->getAll();
//     exit;
// }

http_response_code(404);
echo json_encode(["code" => 404, "data" => "Ruta no encontrada"]);