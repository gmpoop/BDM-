<?php
class KardexModel
{
    private $db; // Conexión a la base de datos

    public function __construct($db)
    {
        $this->db = $db; // Asignación de la conexión
    }

    public function getKardexByUser($usuario_id, $filtros)
    {
        // Consulta SQL base
        $query = "SELECT * FROM KardexUsuario WHERE usuario_id = ?";
        $params = [];
        $types = 'i'; // Inicialmente solo el tipo del usuario_id

        // Agregar filtros opcionales
        if (!empty($filtros['fecha_inicio']) && !empty($filtros['fecha_fin'])) {
            $query .= " AND fecha_inscripcion BETWEEN ? AND ?";
            $params[] = $filtros['fecha_inicio'];
            $params[] = $filtros['fecha_fin'];
            $types .= 'ss'; // Tipos para las fechas
        }

        if (!empty($filtros['categoria'])) {
            $query .= " AND categoria = ?";
            $params[] = $filtros['categoria'];
            $types .= 's'; // Tipo para la categoría
        }

        if (!empty($filtros['estatus'])) {
            $query .= " AND estatus COLLATE utf8mb4_unicode_ci = ?";
            $params[] = $filtros['estatus']; // Agregar el filtro de estatus al array de parámetros
            $types .= 's'; // Tipo para el estatus (es una cadena)
        }     

        $stmt = $this->db->prepare($query);

        // Configurar los parámetros dinámicamente
        if (!empty($params)) {
            $stmt->bind_param($types, $usuario_id, ...$params);
        } else {
            $stmt->bind_param('i', $usuario_id);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

}
