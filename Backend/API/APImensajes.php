<?php
require_once '../clases/Database.php';
require_once '../modelos/mensaje.php';
require_once '../controladores/MessageController.php';
require_once 'C:/xampp/htdocs/BDM-/vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Controlador de mensajes
$controller = new MessageController($db);

// Obtener método HTTP y ruta
$method = $_SERVER['REQUEST_METHOD'];
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Rutas
switch ($request) {
    case '/BDM-/Backend/API/APImensajes.php/DatosRemitente':
        if ($method == 'GET') {
            // Obtener el parámetro ID del curso
            if (isset($_GET['id'])) {
                $idCurso = $_GET['id'];
                $controller->getDatosRemitente($idCurso);  // Llamar al controlador para obtener los datos del curso
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "ID del curso no proporcionado"));
            }
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido"));
        }
        break;

    case '/BDM-/Backend/API/APImensajes.php/mensaje':
        if ($method == 'POST') {
            $controller->create(); // Llamar al método para crear mensaje
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido"));
        }
        break;

    case '/BDM-/Backend/API/APImensajes.php/chat':
        if ($method == 'GET') {
            // Verificar si los parámetros 'usuario_id' y 'curso_id' están presentes
            if (isset($_GET['usuario_id']) && isset($_GET['curso_id'])) {
                $usuario_id = $_GET['usuario_id'];
                $curso_id = $_GET['curso_id'];
                // Llamar al método para obtener los datos del chat
                $controller->getChatData($usuario_id, $curso_id);
            } else {
                // Si faltan los parámetros, devolver un error
                echo json_encode(array("message" => "Faltan parámetros (usuario_id o curso_id)."));
            }
        } else {
            // Si el método no es GET, devolver un error
            http_response_code(405);  // Método no permitido
            echo json_encode(array("message" => "Método no permitido"));
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(array("message" => "Ruta no encontrada"));
        break;
}
