<?php
require_once '../../clases/Database.php';
require_once '../../modelos/ventas.php';
require_once '../../controladores/VentasController.php';
require_once 'C:/xampp/htdocs/BDM-/vendor/autoload.php';


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Controlador de Ventas
$controller = new VentasController($db);

// Obtener método HTTP y ruta
$method = $_SERVER['REQUEST_METHOD'];
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
error_log("REQUEST_URI: " . $_SERVER['REQUEST_URI']);
error_log("Parsed PATH: " . $request);

// Rutas
switch ($request) {
    case '/BDM-/Backend/API/Cursos/APIventas.php/ventas':
        if ($method === 'POST') {
            $controller->createVenta();
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
?>