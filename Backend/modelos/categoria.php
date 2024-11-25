<?php
class Categoria {
    private $conn;

    public $id;
    public $nombre;
    public $descripcion;
    public $usuario_creador_id;
    public $fecha_creacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear una nueva categoría
    public function create() {
        $query = "CALL sp_insertar_categoria(?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->usuario_creador_id = htmlspecialchars(strip_tags($this->usuario_creador_id));

        // Bind
        $stmt->bind_param('ssi', $this->nombre, $this->descripcion, $this->usuario_creador_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Obtener todas las categorías
    public function getAll() {
        $query = "SELECT * FROM categorias"; // No requiere un SP aquí
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Obtener una categoría por ID
    public function getOne($id) {
        $query = "SELECT * FROM categorias WHERE id = ?"; // No requiere un SP aquí
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt;
    }

    // Actualizar una categoría
    public function update() {
        $query = "CALL sp_modificar_categoria(?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);

        // Sanitizar
        $this->nombre = htmlspecialchars(strip_tags($this->nombre));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->usuario_creador_id = htmlspecialchars(strip_tags($this->usuario_creador_id));

        // Bind
        $stmt->bind_param('issi', $this->id, $this->nombre, $this->descripcion, $this->usuario_creador_id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Eliminar una categoría
    public function delete() {
        $query = "CALL sp_borrar_categoria(?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $this->id);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
