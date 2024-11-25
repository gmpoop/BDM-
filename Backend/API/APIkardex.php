<?php
require_once '../clases/Database.php';
require_once '../modelos/kardex.php';
require_once '../controladores/kardexControl.php';
require_once 'C:/xampp/htdocs/BDM/iCraft/vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Controlador del Kardex
$controller = new KardexController($db);

// Obtener método HTTP y ruta
$method = $_SERVER['REQUEST_METHOD'];
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Rutas
switch ($request) {
    case '/BDM/iCraft/Backend/API/APIKardex.php/kardex/estudiante':
        if ($method === 'GET') {
            $controller->getKardexEstudiante();
        } else {
            http_response_code(405);
            echo json_encode(["message" => "Método no permitido"]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["message" => "Ruta no encontrada"]);
        break;
}
