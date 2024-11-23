<?php

class Usuario
{
    private $conn;
    private $table_name = "usuarios";

    // Propiedades
    public $id;
    public $nombre_completo;
    public $genero;
    public $fecha_nacimiento;
    public $foto;
    public $ruta_foto;
    public $email;
    public $contraseña;
    public $fecha_registro;
    public $ultimo_cambio;
    public $intentos_fallidos;
    public $estado;
    public $rol_id;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Método para insertar un nuevo usuario
    public function create()
    {
        // Llamar al procedimiento almacenado para insertar un nuevo usuario
        $query = "CALL sp_insertar_usuario(?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
    
        if (!$stmt) {
            return false;
        }
    
        // Sanitizar inputs
        $this->nombre_completo = htmlspecialchars(strip_tags($this->nombre_completo));
        $this->genero = htmlspecialchars(strip_tags($this->genero));
        $this->fecha_nacimiento = htmlspecialchars(strip_tags($this->fecha_nacimiento));
        $this->foto = htmlspecialchars(strip_tags($this->foto));
        $this->ruta_foto = htmlspecialchars(strip_tags($this->ruta_foto));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->contraseña = password_hash($this->contraseña, PASSWORD_DEFAULT);

        // Bind de los parámetros
        $stmt->bind_param(
            "sssssssi",    
            $this->nombre_completo,
            $this->genero,
            $this->fecha_nacimiento,
            $this->foto,
            $this->ruta_foto,
            $this->email,
            $this->contraseña,
            $this->rol_id
        );
        if ($stmt->execute()) {
            return true;
        } else {
            // Manejo de errores
            return false; 
        }
    }

    // Método para obtener un usuario por ID
    public function read_one()
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->id);  // Usa el marcador de posición y enlaza el parámetro
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->nombre_completo = $row['nombre_completo'];
            $this->genero = $row['genero'];
            $this->fecha_nacimiento = $row['fecha_nacimiento'];
            $this->ruta_foto = $row['ruta_foto'];
            $this->email = $row['email'];
            $this->fecha_registro = $row['fecha_registro'];
            $this->ultimo_cambio = $row['ultimo_cambio'];
            $this->estado = $row['estado'];
            $this->rol_id = $row['rol_id'];
            return true;
        }
        return false;
    }

    // Método para actualizar un usuario
    public function update()
    {
        $query = "CALL sp_modificar_usuario(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
    
        if ($stmt === false) {
            return ["error" => "Error en la preparación de la consulta: " . $this->conn->error];
        }
    
        // Sanitizar inputs
        $this->nombre_completo = htmlspecialchars(strip_tags($this->nombre_completo));
        $this->genero = htmlspecialchars(strip_tags($this->genero));
        $this->email = htmlspecialchars(strip_tags($this->email));
    
        // Verificar si la contraseña necesita ser hasheada
        if (!empty($this->contraseña) && strlen($this->contraseña) < 60) {  // 60 es la longitud típica de un hash bcrypt
            $this->contraseña = password_hash($this->contraseña, PASSWORD_DEFAULT);
        }
    
        $stmt->bind_param(
            "issssbssi",
            $this->id,
            $this->nombre_completo,
            $this->genero,
            $this->fecha_nacimiento,
            $this->foto,
            $this->ruta_foto,
            $this->email,
            $this->contraseña,
            $this->rol_id
        );
    
        try {
            if ($stmt->execute()) {
                return ["success" => true, "message" => "Usuario actualizado con éxito"];
            } else {
                return ["error" => "Error al actualizar el usuario: " . $stmt->error];
            }
        } catch (Exception $e) {
            return ["error" => "Excepción al actualizar el usuario: " . $e->getMessage()];
        } finally {
            $stmt->close();
        }
    }

    // Método para eliminar un usuario
    public function delete()
    {

        try {            
            $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s", $this->email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // Manejar la respuesta del SELECT aquí, por ejemplo, asignar valores a las propiedades del objeto
                $this->id = $row['id'];
            } else {
                // Manejar el caso en que no se encuentre el usuario
                return false;
            }
            
        } catch (\Throwable $th) {
            //throw $th;
        }

        $query = "CALL sp_borrar_usuario(?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para obtener todos los usuarios
    public function read()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Método para verificar si el email ya existe
    public function emailExists()
    {
        $query = "SELECT id, contraseña, rol_id FROM " . $this->table_name . " WHERE email = ? 
        AND estado = 'activo' LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $this->id = $row['id'];
            $this->contraseña = $row['contraseña'];
            $this->rol_id = $row['rol_id'];
            return true;
        }
        return false;
    }

    // Método para actualizar los intentos fallidos
    public function updateFailedAttempts($attempts)
    {
        $query = "UPDATE " . $this->table_name . " SET intentos_fallidos = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $attempts, $this->id);
        return $stmt->execute();
    }

    // Método para actualizar el estado del usuario
    public function updateStatus($status)
    {
        $query = "UPDATE " . $this->table_name . " SET estado = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("si", $status, $this->id);
        return $stmt->execute();
    }
}