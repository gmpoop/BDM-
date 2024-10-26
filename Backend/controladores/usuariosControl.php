<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
;

class usuariosControl
{
    private $db;
    private $user;

    public function __construct($db)
    {
        $this->db = $db;
        $this->user = new Usuario($db);
    }

    public function register()
    {
        // Obtener datos POST
        $data = json_decode(file_get_contents("php://input"));

        // Asegurar que los datos no estén vacíos
        if (
            !empty($data->nombre_completo) &&
            !empty($data->email) &&
            !empty($data->contraseña)
        ) {
            // Establecer valores de propiedad de usuario
            $this->user->nombre_completo = $data->nombre_completo;
            $this->user->email = $data->email;
            $this->user->contraseña = $data->contraseña;
            $this->user->genero = $data->genero ?? null;
            $this->user->fecha_nacimiento = $data->fecha_nacimiento ?? null;
            $this->user->rol_id = $data->rol_id ?? 2;

            // Crear el usuario
            if ($this->user->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Usuario creado con éxito."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo crear el usuario."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "No se puede crear el usuario. Datos incompletos."));
        }
    }

    public function login()
    {
        // Obtener datos POST
        $data = json_decode(file_get_contents("php://input"));

        // Establecer valores de propiedad de usuario
        $this->user->email = $data->email;
        $email_exists = $this->user->emailExists();

        // Verificar si el email existe y si la contraseña es correcta
        if ($email_exists && password_verify($data->contraseña, $this->user->contraseña)) {
            // Generar JWT
            $token = array(
                "iss" => "http://localhost",
                "aud" => "http://localhost",
                "iat" => time(),
                "nbf" => time(),
                "exp" => time() + (60 * 60), // Válido por 1 hora
                "data" => array(
                    "id" => $this->user->id,
                    "email" => $this->user->email,
                    "rol_id" => $this->user->rol_id
                )
            );

            $secret_key = $_ENV['JWT_SECRET_KEY'] ?? null;
            if ($secret_key === null) {
                throw new Exception("JWT_SECRET_KEY no está definida");
            }
            $jwt = JWT::encode($token, $secret_key, 'HS256');

            http_response_code(200);
            echo json_encode(
                array(
                    "message" => "Inicio de sesión exitoso.",
                    "jwt" => $jwt
                )
            );
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "Inicio de sesión fallido."));
        }
    }
    public function getUser($id)
    {
        $this->user->id = $id;
        if ($this->user->read_one()) {
            $user_arr = array(
                "id" => $this->user->id,
                "nombre_completo" => $this->user->nombre_completo,
                "email" => $this->user->email,
                "genero" => $this->user->genero,
                "fecha_nacimiento" => $this->user->fecha_nacimiento,
                "ruta_foto" => $this->user->ruta_foto,
                "fecha_registro" => $this->user->fecha_registro,
                "estado" => $this->user->estado,
                "rol_id" => $this->user->rol_id
            );
            http_response_code(200);
            echo json_encode($user_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Usuario no encontrado."));
        }
    }

    public function updateUser($userId)
    {
        // Obtener datos del cuerpo de la solicitud
        $data = json_decode(file_get_contents("php://input"));
        
        // Verificar que se recibieron datos
        if (!$data) {
            http_response_code(400);
            echo json_encode(["message" => "No se recibieron datos para actualizar"]);
            return;
        }
    
        $user = new Usuario($this->db);
        $user->id = $userId;
        
        // Asignar los datos recibidos a las propiedades del usuario
        $user->nombre_completo = $data->nombre_completo ?? null;
        $user->genero = $data->genero ?? null;
        $user->fecha_nacimiento = $data->fecha_nacimiento ?? null;
        $user->foto = $data->foto ?? null;
        $user->ruta_foto = $data->ruta_foto ?? null;
        $user->email = $data->email ?? null;
        $user->contraseña = $data->contraseña ?? null;
        $user->rol_id = $data->rol_id ?? null;
    
        $result = $user->update();
    
        if (isset($result['success'])) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(500);
            echo json_encode($result);
        }
    }
    
    public function deleteUser($id)
    {
        $this->user->id = $id;

        if ($this->user->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Usuario eliminado con éxito."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "No se pudo eliminar el usuario."));
        }
    }
    public function getAllUsers()
    {
        $stmt = $this->user->read();
        $num = $stmt->num_rows;

        if ($num > 0) {
            $users_arr = array();
            $users_arr["records"] = array();

            while ($row = $stmt->fetch_assoc()) {
                extract($row);
                $user_item = array(
                    "id" => $id,
                    "nombre_completo" => $nombre_completo,
                    "email" => $email,
                    "genero" => $genero,
                    "fecha_nacimiento" => $fecha_nacimiento,
                    "ruta_foto" => $ruta_foto,
                    "fecha_registro" => $fecha_registro,
                    "estado" => $estado,
                    "rol_id" => $rol_id
                );
                array_push($users_arr["records"], $user_item);
            }

            http_response_code(200);
            echo json_encode($users_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron usuarios."));
        }
    }
    public function updateUserStatus($id, $status)
    {
        $this->user->id = $id;
        if ($this->user->updateStatus($status)) {
            http_response_code(200);
            echo json_encode(array("message" => "Estado del usuario actualizado con éxito."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "No se pudo actualizar el estado del usuario."));
        }
    }
}