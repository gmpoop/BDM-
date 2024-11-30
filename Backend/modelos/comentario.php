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
    
    public function getComentariosPorCurso($curso_id) {
        $query = "SELECT * FROM vista_comentarios_cursos WHERE curso_id = ?"; 
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $curso_id);
        $stmt->execute();
        return $stmt;
    }

}
