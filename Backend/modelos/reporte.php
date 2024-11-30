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

    public function getReporteEstudiantePorId($id)
    {
        $query = "SELECT * FROM view_reporte_usuario_estudiantes WHERE usuario_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getReporteTutorPorId($id)
    {
        $query = "SELECT * FROM view_reporte_usuario_tutores WHERE usuario_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function getReporteCadaCurso($id_instructor) {
        $query = "
                SELECT DISTINCT
                    c.id AS curso_id,
                    c.titulo AS curso,
                    u.nombre_completo AS comprador_nombre,
                    u.email AS comprador_email,
                    v.fecha_venta AS fecha_compra,
                    i.progreso
                FROM 
                    cursos c
                INNER JOIN 
                    ventas v ON c.id = v.curso_id
                INNER JOIN 
                    usuarios u ON v.comprador_id = u.id
                INNER JOIN 
                    inscripciones i ON v.comprador_id = i.usuario_id
                WHERE 
                    c.instructor_id = ?
                ORDER BY 
                    c.titulo, v.fecha_venta;

        ";

        $stmt = $this->conn->prepare($query);

        if (!$stmt) {
            throw new Exception("Error preparando la consulta: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id_instructor); // 'i' indica que el parámetro es un entero
        $stmt->execute();
        $result = $stmt->get_result();

        if (!$result) {
            throw new Exception("Error ejecutando la consulta: " . $this->conn->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function reporteCursos($instructor_id) {
        $sql = "
            SELECT 
                c.id AS curso_id,
                c.titulo AS nombre_curso,
                c.ruta_imagen AS imagen_curso,
                COUNT(i.id) AS cantidad_inscritos,
                (COUNT(i.id) * c.costo) AS total_ganancias
            FROM 
                cursos c
            LEFT JOIN 
                inscripciones i ON c.id = i.curso_id
            WHERE 
                c.instructor_id = ?
            GROUP BY 
                c.id, c.titulo, c.imagen, c.costo;
        ";

        // Preparar la consulta
        $stmt = $this->conn->prepare($sql);

        // Verificar si la preparación fue exitosa
        if ($stmt === false) {
            die('Error al preparar la consulta: ' . $this->conn->error);
        }

        // Vincular el parámetro
        $stmt->bind_param('i', $instructor_id); // 'i' significa que es un entero

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener los resultados
        $result = $stmt->get_result();
        
        // Devolver los resultados como un arreglo asociativo
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>