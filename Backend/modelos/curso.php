<?php
class Curso {
    private $conn;

    public $id;
    public $titulo;
    public $descripcion;
    public $imagen;
    public $ruta_imagen;
    public $costo;
    public $estado;
    public $categoria_id;
    public $instructor_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear una nueva categoría
    public function create() {
        $query = "CALL sp_insertar_curso(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
    
        // Sanitizar
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->imagen = htmlspecialchars(strip_tags($this->imagen));
        $this->ruta_imagen = htmlspecialchars(strip_tags($this->ruta_imagen));
        $this->costo = htmlspecialchars(strip_tags($this->costo));
        $this->categoria_id = htmlspecialchars(strip_tags($this->categoria_id));
        $this->instructor_id = htmlspecialchars(strip_tags($this->instructor_id));
    
        // Bind
        $stmt->bind_param('ssssiii', $this->titulo, $this->descripcion, $this->imagen, $this->ruta_imagen, $this->costo, $this->categoria_id, $this->instructor_id);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Obtener todas las categorías
    public function getAll() {
        $query = "SELECT * FROM cursos"; // No requiere un SP aquí
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener una categoría por ID
    public function getOne($id) {
        $query = "SELECT * FROM cursps WHERE id = ?"; // No requiere un SP aquí
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt;
    }

    public function getOneBy($title, $instructor_id) {
        // Consulta para seleccionar el curso basado en título e ID de instructor
        $query = "SELECT * FROM cursos WHERE titulo = ? AND instructor_id = ?";
    
        // Preparar la declaración
        $stmt = $this->conn->prepare($query);
    
        // Enlazar los parámetros a los marcadores de posición
        $stmt->bind_param('si', $title, $instructor_id);
    
        // Ejecutar la declaración
        $stmt->execute();
    
        // Retornar el statement para el manejo posterior de los resultados
        return $stmt;
    }
    

    // Actualizar una categoría
    // public function update() {
    //     $query = "CALL sp_modificar_categoria(?, ?, ?, ?)";
    //     $stmt = $this->conn->prepare($query);

    //     // Sanitizar
    //     $this->nombre = htmlspecialchars(strip_tags($this->nombre));
    //     $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
    //     $this->usuario_creador_id = htmlspecialchars(strip_tags($this->usuario_creador_id));

    //     // Bind
    //     $stmt->bind_param('issi', $this->id, $this->nombre, $this->descripcion, $this->usuario_creador_id);

    //     if ($stmt->execute()) {
    //         return true;
    //     }
    //     return false;
    // }

    // // Eliminar una categoría
    // public function delete() {
    //     $query = "CALL sp_borrar_categoria(?)";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bind_param('i', $this->id);

    //     if ($stmt->execute()) {
    //         return true;
    //     }
    //     return false;
    // }
}
