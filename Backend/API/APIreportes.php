<?php
require_once '../clases/Database.php';
require_once '../modelos/reporte.php';
require_once '../controladores/reporteControl.php';
require_once 'C:/xampp/htdocs/BDM/iCraft/vendor/autoload.php';

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
    case '/BDM/iCraft/Backend/API/APIReportes.php/reporte/Estudiantes':
        if ($method == 'GET') {
            $controller->getReporteEstudiante();
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido"));
        }
        break;

    case '/BDM/iCraft/Backend/API/APIReportes.php/reporte/Tutores':
        if ($method == 'GET') {
            $controller->getReporteTutores();
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido"));
        }
        break;

    case '/BDM/iCraft/Backend/API/APIReportes.php/reporte/ReportesTotales':
        if ($method == 'GET') {
            $controller->getReportesTotales();
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido"));
        }
        break;

    case '/BDM/iCraft/Backend/API/APIReportes.php/reporte/Estudiantes/Buscar':
        if ($method == 'GET') {
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $controller->getReporteEstudiantePorId($id);
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Falta el parámetro 'id'."));
            }
        } else {
            http_response_code(405);
            echo json_encode(array("message" => "Método no permitido"));
        }
        break;

    case '/BDM/iCraft/Backend/API/APIReportes.php/reporte/Tutores/Buscar':
        if ($method == 'GET') {
            if (isset($_GET['id'])) {
                $id = intval($_GET['id']);
                $controller->getReporteTutorPorId($id);
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Falta el parámetro 'id'."));
            }
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
