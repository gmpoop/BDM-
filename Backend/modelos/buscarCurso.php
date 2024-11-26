<?php
class buscarCursos {
    
    private $conn;

    // Propiedades del curso
    private $id;
    private $titulo;
    private $descripcion;
    private $imagen;
    private $ruta_foto;
    private $costo;
    private $estado;
    private $categoria_id;
    private $instructor_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Método actualizado para buscar cursos con filtros adicionales
    public function buscarCursos($titulo = null, $categoria_id = null, $instructor_id = null, $estado = null, $start_date = null, $end_date = null) {
        $query = "SELECT id, titulo, descripcion, ruta_imagen, costo, estado, categoria_id, instructor_id 
                  FROM cursos WHERE 1=1";
        
        $params = [];
        
        // Añadir filtros si están definidos
        if (!is_null($titulo)) {
            $query .= " AND (titulo LIKE ? OR descripcion LIKE ?)";
            $params[] = "%" . $titulo . "%";
            $params[] = "%" . $titulo . "%";
        }
        if (!is_null($categoria_id)) {
            $query .= " AND categoria_id = ?";
            $params[] = $categoria_id;
        }
        if (!is_null($instructor_id)) {
            $query .= " AND instructor_id = ?";
            $params[] = $instructor_id;
        }
        if (!is_null($estado)) {
            $query .= " AND estado = ?";
            $params[] = $estado;
        }
        if (!is_null($start_date)) {
            $query .= " AND DATE(fecha_creacion) >= ?";
            $params[] = $start_date;
        }
        if (!is_null($end_date)) {
            $query .= " AND DATE(fecha_creacion) <= ?";
            $params[] = $end_date;
        }

        // Preparar y ejecutar la consulta
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        
        // Obtener los resultados
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultados;
    }
}
?>
