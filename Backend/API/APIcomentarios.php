<?php
require_once '../clases/Database.php';
require_once '../modelos/comentario.php';
require_once '../controladores/comentariosControl.php';
require_once 'C:/xampp/htdocs/BDM/iCraft/vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Controlador de comentarios
$controller = new comentariosController($db);

// Obtener método HTTP y ruta
$method = $_SERVER['REQUEST_METHOD'];
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Rutas
switch ($request) {
    case '/BDM/iCraft/Backend/API/APIcomentarios.php/comentarios': 
        if ($method == 'GET') {
            $controller->getAllComentarios();
        } elseif ($method == 'POST') {
            $controller->createComentario();
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido"));
        }
        break;

    case preg_match('/^\/BDM\/iCraft\/Backend\/API\/APIcomentarios.php\/comentario\/(\d+)$/', $request, $matches) ? true : false:
        $id = $matches[1];
        if ($method == 'GET') {
            $controller->getComentario($id);
        } elseif ($method == 'PUT') {
            $controller->updateComentario($id);
        } elseif ($method == 'DELETE') {
            $controller->deleteComentario($id);
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
