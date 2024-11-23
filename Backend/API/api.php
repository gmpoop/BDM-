<?php
require_once '../clases/Database.php';
require_once '../modelos/usuarios.php';
require_once '../controladores/usuariosControl.php';
require_once 'C:/xampp/htdocs/BDM-/vendor/autoload.php';


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

// Instanciar el controlador de usuario
$userController = new usuariosControl($db);

// Obtener el método HTTP y la ruta
$method = $_SERVER['REQUEST_METHOD'];
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Rutas de la API
switch ($request) {
    case '/BDM-/Backend/API/api.php/register': {
        if ($method == 'POST') {
            $userController->register();
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido"));
        }
    }
        break;

    case '/BDM-/Backend/API/api.php/login': {
        if ($method == 'POST') {
            $userController->login();
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido"));
        }
    }
        break;

        
    case '/BDM-/Backend/API/api.php/users': {
        if ($method == 'GET') {
            // Verificar el token JWT
            $headers = getallheaders();
            if (isset($headers['Authorization'])) {
                $token = str_replace('Bearer ', '', $headers['Authorization']);
                try {
                    // Decodificar el token
                    $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
                    
                    $userController->getAllUsers();
                } catch (Exception $e) {
                    http_response_code(401);
                    echo json_encode(array("message" => "Token no válido o expirado"));
                }
            } else {
                http_response_code(401);
                echo json_encode(array("message" => "Se requiere autorización"));
            }
        } 
        else if ($method == 'PUT') {

                $userController->updateUser();
        }
        else if ($method == 'DELETE') {

                $userController->deleteUser();
        }
        else {
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido"));
        }
    }
        break;

    case (preg_match('/^\/BDM-\/Backend\/API\/api.php\/user\/(\d+)$/', $request, $matches) ? true : false): {
        // Verificar el token JWT
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $token = str_replace('Bearer ', '', $headers['Authorization']);

            try {
                $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
                error_log(print_r($decoded, true)); // Imprimir la variable en la consola
                $userId = $decoded->data->id;

                switch ($method) {
                    case 'GET':
                        $userController->getUser($userId);
                        break;
                    case 'PUT':
                        $userController->updateUser($userId);
                        break;
                    case 'DELETE':
                        $userController->deleteUser($userId);
                        break;
                    default:
                        http_response_code(405);
                        echo json_encode(array("message" => "Método no permitido"));
                }
            } catch (Exception $e) {
                http_response_code(401);
                echo json_encode(array("message" => "Token no válido"));
            }
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Se requiere autorización"));
        }
    }
        break;

    case (preg_match('/^\/BDM\/BDM\/Backend\/API\/api.php\/users\/(\d+)\/status\/(\w+)$/', $request, $matches) ? true : false): {
        $userId = $matches[1];
        $status = $matches[2];
        if ($method == 'PUT') {
            // Verificar el token JWT
            $headers = getallheaders();
            if (isset($headers['Authorization'])) {
                $token = str_replace('Bearer ', '', $headers['Authorization']);
                try {
                    $decoded = JWT::decode($token, new Key($_ENV['JWT_SECRET_KEY'], 'HS256'));
                    // Verificar si el usuario tiene permisos de administrador
                    if ($decoded->data->rol_id == 1) { // Asumiendo que rol_id 1 es para administradores
                        $userController->updateUserStatus($userId, $status);
                    } else {
                        http_response_code(403);
                        echo json_encode(array("message" => "No tiene permisos para realizar esta acción"));
                    }
                } catch (Exception $e) {
                    http_response_code(401);
                    echo json_encode(array("message" => "Token no válido"));
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