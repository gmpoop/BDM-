<?php
class buscarCursos
{

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

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Método actualizado para buscar cursos con filtros adicionales
    public function buscarCursos($titulo = null, $categoria_id = null, $instructor_id = null, $estado = null, $start_date = null, $end_date = null)
    {
        // Base de la consulta
        $query = "SELECT cur.categoria_id, cat.nombre as nombre_categoria, cur.id as id_curso, cur.titulo, cur.descripcion, cur.imagen, cur.costo 
                  FROM cursos cur 
                  JOIN categorias cat ON cur.categoria_id = cat.id 
                  WHERE 1=1";

        // Arreglo para los tipos de los parámetros y los valores de los parámetros
        $types = '';
        $params = [];

        // Añadir filtros si están definidos
        if (!is_null($titulo)) {
            $query .= " AND (cur.titulo LIKE ? OR cur.descripcion LIKE ?)";
            $types .= 'ss';
            $params[] = "%" . $titulo . "%";
            $params[] = "%" . $titulo . "%";
        }
        if (!is_null($categoria_id)) {
            $query .= " AND cur.categoria_id = ?";
            $types .= 'i';
            $params[] = $categoria_id;
        }
        if (!is_null($instructor_id)) {
            $query .= " AND cur.instructor_id = ?";
            $types .= 'i';
            $params[] = $instructor_id;
        }
        if (!is_null($estado)) {
            $query .= " AND cur.estado = ?";
            $types .= 's';
            $params[] = $estado;
        }

        // Preparar y ejecutar la consulta
        $stmt = $this->conn->prepare($query);

        if ($types && $params) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();

        // Obtener los resultados
        return $stmt;
    }
}
