<?php
require_once '../../clases/Database.php';
require_once '../../modelos/curso.php';
require_once '../../controladores/cursosControlador.php';
require_once 'C:/xampp/htdocs/BDM-/vendor/autoload.php';

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();


//Agrear controlador de cursos
$controller = new cursosControlador($db);



// Obtener método HTTP y ruta
$method = $_SERVER['REQUEST_METHOD'];
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($request) {
    case '/BDM-/Backend/API/Cursos/APICursos.php/cursos':
        if ($method == 'GET') {
            $controller->getAllCursos();
        } elseif ($method == 'POST') {
            //Agregar la creacion del curso
            $controller->create();
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