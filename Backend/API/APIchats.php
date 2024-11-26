<?php

require_once '../clases/Database.php';
require_once '../modelos/usuarios.php';
require_once '../controladores/usuariosControl.php';
require_once '../controladores/chatController.php';
require_once '../controladores/messageController.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

// Configurar encabezados
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Obtener la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Instanciar los controladores
$chatController = new ChatController($db);
$messageController = new MessageController($db);

// Obtener el método HTTP y la ruta
$method = $_SERVER['REQUEST_METHOD'];
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Rutas de la API de chats
switch ($request) {
    // Obtener chats de un usuario
    case '/BDM-/Backend/API/apiChats.php/chats': {
        if ($method == 'GET') {
            // Verificar el token JWT
            $headers = getallheaders();
            if (isset($headers['Authorization'])) {
                $token = str_replace('Bearer ', '', $headers['Authorization']);
                try {
                    // Decodificar el token
                    $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
                    $userId = $decoded->data->id;

                    // Llamar al controlador para obtener los chats
                    $chatController->getChats($userId);
                } catch (Exception $e) {
                    http_response_code(401);
                    echo json_encode(array("message" => "Token no válido o expirado"));
                }
            } else {
                http_response_code(401);
                echo json_encode(array("message" => "Se requiere autorización"));
            }
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido"));
        }
    }
    break;

    // Obtener mensajes de un chat entre dos usuarios
    case '/BDM-/Backend/API/apiChats.php/messages': {
        if ($method == 'GET') {
            // Verificar el token JWT
            $headers = getallheaders();
            if (isset($headers['Authorization'])) {
                $token = str_replace('Bearer ', '', $headers['Authorization']);
                try {
                    // Decodificar el token
                    $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
                    $userId = $decoded->data->id;
                    $personaId = isset($_GET['persona_id']) ? $_GET['persona_id'] : null;

                    if ($personaId) {
                        // Llamar al controlador para obtener los mensajes
                        $messageController->getMessages($userId, $personaId);
                    } else {
                        http_response_code(400);
                        echo json_encode(array("message" => "El ID de la persona no fue proporcionado"));
                    }
                } catch (Exception $e) {
                    http_response_code(401);
                    echo json_encode(array("message" => "Token no válido o expirado"));
                }
            } else {
                http_response_code(401);
                echo json_encode(array("message" => "Se requiere autorización"));
            }
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido"));
        }
    }
    break;

    default: {
        http_response_code(404);
        echo json_encode(array("message" => "Ruta no encontrada"));
    }
    break;
}
?>
