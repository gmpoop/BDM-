<?php

class Message
{
    private $conn;
    private $table_name = "mensajes";

    // Propiedades
    public $id;
    public $remitente_id;
    public $destinatario_id;
    public $mensaje;
    public $chat_id;

    // Constructor con la conexión a la base de datos
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Método para crear un nuevo mensaje
    public function create()
    {
        $query = "INSERT INTO " . $this->table_name . " (remitente_id, destinatario_id, mensaje, chat_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        // Sanitizar datos
        $this->remitente_id = htmlspecialchars(strip_tags($this->remitente_id));
        $this->destinatario_id = htmlspecialchars(strip_tags($this->destinatario_id));
        $this->mensaje = htmlspecialchars(strip_tags($this->mensaje));
        $this->chat_id = htmlspecialchars(strip_tags($this->chat_id));

        // Bind de los parámetros
        $stmt->bind_param('iisi', $this->remitente_id, $this->destinatario_id, $this->mensaje, $this->chat_id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Método para obtener datos del remitente
    public function getDatosRemitente($id)
    {
        $query = "SELECT * FROM curso_instructor_view WHERE id_curso = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Método para obtener los datos del chat
    public function getDatosChat($id)
    {
        $query = "SELECT * FROM chats WHERE curso_perteneciente = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result();
    }

    // Método para obtener los datos del chat según el usuario y el curso
    public function getChatDataByUserAndCourse($usuario_id, $curso_id)
    {
        $query = "
            SELECT * FROM datachat
            WHERE 
                (usuario_1_id = ? OR usuario_2_id = ?) AND curso_perteneciente = ?
        ";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('iii', $usuario_id, $usuario_id, $curso_id);  // Enlazar los parámetros
        $stmt->execute();
    
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC); // Retorna los resultados como un array asociativo
    }
    
}
?>