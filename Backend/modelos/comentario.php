<?php
class Comentario {
    private $conn;

    public $id;
    public $curso_id;
    public $usuario_id;
    public $comentario;
    public $calificacion;
    public $fecha_creacion;
    public $fecha_eliminacion;
    public $causa_eliminacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear un nuevo comentario
    public function create() {
        $query = "CALL sp_insertar_comentario(?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->curso_id = htmlspecialchars(strip_tags($this->curso_id));
        $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
        $this->comentario = htmlspecialchars(strip_tags($this->comentario));
        $this->calificacion = htmlspecialchars(strip_tags($this->calificacion));

        // Bind
        $stmt->bind_param('iisi', $this->curso_id, $this->usuario_id, $this->comentario, $this->calificacion);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Obtener todos los comentarios
    public function getAll() {
        $query = "SELECT * FROM comentarios"; 
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener un comentario por ID
    public function getOne($id) {
        $query = "SELECT * FROM comentarios WHERE id = ?"; 
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt;
    }
    public function getperCurso($curso_id) {
        $query = "SELECT * FROM comentarios WHERE curso_id = ?"; 
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $curso_id);
        $stmt->execute();
        return $stmt;
    }
    // Actualizar un comentario
    public function update() {
        $query = "CALL sp_modificar_comentario(?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->curso_id = htmlspecialchars(strip_tags($this->curso_id));
        $this->usuario_id = htmlspecialchars(strip_tags($this->usuario_id));
        $this->comentario = htmlspecialchars(strip_tags($this->comentario));
        $this->calificacion = htmlspecialchars(strip_tags($this->calificacion));

        // Bind
        $stmt->bind_param('iiisi', $this->id, $this->curso_id, $this->usuario_id, $this->comentario, $this->calificacion); 

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar un comentario
    public function delete() {
        $query = "CALL sp_borrar_comentario(?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
