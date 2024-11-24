<?php
require_once '../clases/Database.php';
require_once '../modelos/reporte.php';
require_once '../controladores/reporteControl.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Controlador de reportes
$controller = new ReportesController($db);

// Obtener método HTTP y ruta
$method = $_SERVER['REQUEST_METHOD'];
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Rutas
switch ($request) {
    case '/BDM/BDM/Backend/API/APIReportes.php/reportes': 
        if ($method == 'GET') {
            $controller->getAllReportes();
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido"));
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(array("message" => "Ruta no encontrada"));
        break;
}
