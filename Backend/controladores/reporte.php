<?php
class Reporte
{
    private $conn;
    private $table_name = "view_reporte_usuario_estudiantes"; // Vista en la base de datos

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Obtener todos los reportes de la vista
    public function getReporteEstudiante()
    {
        $query = "SELECT * FROM view_reporte_usuario_estudiantes";  // Ajusta el nombre de la vista si es necesario

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getReporteTutores()
    {
        $query = "SELECT * FROM view_reporte_usuario_tutores";  // Ajusta el nombre de la vista si es necesario

        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function obtenerReportesTotales()
    {
        $query = "SELECT * FROM reportes_totales";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
}
?>