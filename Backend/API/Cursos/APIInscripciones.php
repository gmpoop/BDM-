<?php
require_once '../../clases/Database.php';
require_once '../../modelos/inscripciones.php';
require_once '../../controladores/inscripcionesControlador.php';
require_once 'C:/xampp/htdocs/iCraft/vendor/autoload.php';


header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Controlador de Ventas
$controller = new inscripcionesControlador($db);

// Obtener método HTTP y ruta
$method = $_SERVER['REQUEST_METHOD'];
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
error_log("REQUEST_URI: " . $_SERVER['REQUEST_URI']);
error_log("Parsed PATH: " . $request);

// Rutas
switch ($request) {
    case '/iCraft/Backend/API/Cursos/APIInscripciones.php/inscripciones':
        if ($method === 'GET') {
            $controller->ConseguirInscripcion();
            http_response_code(200);
        }
   
        break;

    case '/iCraft/Backend/API/Cursos/APIInscripciones.php/inscripcion':
        if ($method === 'PUT') {
            $controller->ActualizarProgreso();
        }
        else {
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