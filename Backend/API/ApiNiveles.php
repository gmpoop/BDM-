<?php
require_once '../clases/Database.php';//Nivel
require_once '../modelos/Nivel.php';
require_once '../controladores/nivelControlador.php';

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

$NivelControlador = new NivelControlador($db);


$method = $_SERVER['REQUEST_METHOD'];
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($request) { 
    case '/BDM-/Backend/API/ApiNiveles.php/nivel': {

            $headers = getallheaders();
    
            switch ($method) {
                case 'POST':
                    $NivelControlador->Create();
                    break;
                case 'PUT':
                    $NivelControlador->GetNivel();
                    break;

                default:
                    http_response_code(405);
                    echo json_encode(array("message" => "Método no permitido"));
            }
    
        }
            break;
     case '/BDM-/Backend/API/api.php/niveles': {

            $headers = getallheaders();
    
            switch ($method) {
                case 'PUT':
                    $NivelControlador->GetNiveles();
                    break;
                default:
                    http_response_code(405);
                    echo json_encode(array("message" => "Método no permitido"));
            }
    
        }
            break;
    default:
        http_response_code(404);
        echo json_encode(array("message" => "Ruta no encontrada"));
        break;
}
