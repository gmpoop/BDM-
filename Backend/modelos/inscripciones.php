<?php
class Inscripciones {
    private $conn;
    private $table_name = "inscripciones";

    public $id;
    public $usuario_id;
    public $curso_id;
    public $fecha_inscripcion;
    public $progreso;
    public $niveles_completados;
    public $fecha_terminacion;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function ConseguirInscripciones() {
        $query = "SELECT * FROM inscripciones";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    public function ConseguirInscripcionesDetalle($id) {
        $query = "SELECT * FROM inscripciones WHERE id = ?";

        $stmt = $this->conn->prepare($query);

        $stmt->bind_param('i', $id);
        
        $stmt->execute();
        return $stmt;
    }

    public function Actualizar() {
        // Consulta para llamar al procedimiento almacenado
        $query = "CALL 	sp_modificar_inscripcion(?, ?, ?, ?)";  
    
        // Preparar la consulta
        $stmt = $this->conn->prepare($query);
    
        // Verificar si la preparación fue exitosa
        if ($stmt === false) {
            return false;
        }
    
        // Sanitizar y enlazar los parámetros (tipo: i = entero, s = string, d = decimal)
        $id = htmlspecialchars(strip_tags($this->id));
        $fecha_terminacion = htmlspecialchars(strip_tags($this->fecha_terminacion));
        $progreso = htmlspecialchars(strip_tags($this->progreso));
        $nivel_completado = htmlspecialchars(strip_tags($this->niveles_completados));
        
        // Parsear progreso y id como enteros
        $id = (int)$id;
        $progreso = (int)$progreso;

        // Enlazar los parámetros con sus respectivos tipos
        $stmt->bind_param('iids', $id, $progreso,  $fecha_terminacion, $nivel_completado);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            return true;
        }
        // Si algo sale mal, retornar false
        return false;
    }
    
}

?>