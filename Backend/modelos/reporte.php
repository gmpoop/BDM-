<?php
class Reporte {
    private $conn;
    private $table_name = "reporte_estudiantes"; // Vista en la base de datos

    public function __construct($db) {
        $this->conn = $db;
    }

    // Obtener todos los reportes de la vista
    public function getAllReportes() {
        $query = "SELECT correo, nombre_completo, fecha_ingreso, cursos_inscritos, porcentaje_cursos_terminados FROM " . $this->table_name;

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        // Si hay resultados, devolverlos
        if ($result->num_rows > 0) {
            return $result; // Retornamos directamente el resultado
        } else {
            return null; // No hay registros
        }
    }
}
