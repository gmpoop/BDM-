<?php
require_once '../clases/Database.php';
require_once '../modelos/categoria.php';
require_once '../controladores/categoriasControl.php';
require_once 'C:/xampp/htdocs/BDM/BDM/vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Controlador de categorías
$controller = new CategoriasController($db);

// Obtener método HTTP y ruta
$method = $_SERVER['REQUEST_METHOD'];
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Rutas
switch ($request) {
    case '/BDM/BDM/Backend/API/APIcategorias.php/categorias': 
        if ($method == 'GET') {
            $controller->getAllCategorias();
        } elseif ($method == 'POST') {
            $controller->createCategoria();
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido"));
        }
        break;

    case preg_match('/^\/BDM\/BDM\/Backend\/API\/APIcategorias.php\/categoria\/(\d+)$/', $request, $matches) ? true : false:
        $id = $matches[1];
        if ($method == 'GET') {
            $controller->getCategoria($id);
        } elseif ($method == 'PUT') {
            $controller->updateCategoria($id);
        } elseif ($method == 'DELETE') {
            $controller->deleteCategoria($id);
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
