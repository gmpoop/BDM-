<?php
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

require_once 'C:/xampp/htdocs/iCraft/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

class Middleware {
    private $secret_key;
    private $token;

    public function __construct()
    {
        $this->secret_key = $_ENV['JWT_SECRET_KEY'] ?? '';
        $this->token = '';
    }

    public function verifyToken($requestHeaders) {
        $secret_key = $this->secret_key;
        
        // Buscar el encabezado 'Authorization'
        if (isset($requestHeaders['Authorization'])) {
            $authHeader = $requestHeaders['Authorization'];
            $this->token = str_replace('Bearer ', '', $authHeader);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Header de autorización no encontrado']);
            exit();
        }
    
        try {
            // Decodificar el token
            $decoded = JWT::decode($this->token, new Key($secret_key, 'HS256'));
    
            // Aquí puedes verificar los datos del token, por ejemplo:
            // verificar que el token no haya expirado
            $now = time();
            if ($decoded->exp < $now) {
                http_response_code(401);
                echo json_encode(['message' => 'El token ha expirado']);
                exit();
            }
    
            return true; // Token es válido
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid token', 'error' => $e->getMessage()]);
            exit();
        }
    }
}
