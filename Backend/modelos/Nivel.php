
<?php
class Nivel
{
    private $db; // Conexión a la base de datos
    private $table_name = "niveles";

    // Propiedades
    public $id;
    public $curso_id;
    public $titulo;
    public $contenido;
    public $video;
    public $estado;

    // Constructor para inicializar la conexión a la base de datos
    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    /////
    // Método para obtener los datos del administrador
    /////

    public function CrearNivel()
    {
        // Consulta para llamar al procedimiento almacenado
        $query = "CALL sp_insertar_nivel(?, ?, ?, ?)";

        // Preparar la declaración
        $stmt = $this->db->prepare($query);

        // Enlazar los parámetros
        $stmt->bind_param("isss", $this->curso_id, $this->titulo, $this->contenido, $this->video);

        // Ejecutar la declaración
        if ($stmt->execute()) {
            return true;
        }

        return false;
    }


    public function ObtenerNivel($id)
    {
        $query = "SELECT * FROM {$this->table_name} WHERE id = ?";

        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);  // Usa el marcador de posición y enlaza el parámetro

        $stmt->execute();

        // Obtener el resultado de la consulta
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Devuelve los datos del nivel
        } else {
            return false; // No se encontró el nivel
        }
    }


    public function ObtenerNiveles($curso_id) {
        $query = "
            SELECT niv.*, cur.imagen AS imagen_curso 
            FROM niveles niv
            JOIN cursos cur ON cur.id = niv.curso_id
            WHERE niv.curso_id = ?
        ";
    
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $curso_id);  // Usa el marcador de posición y enlaza el parámetro
    
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            $niveles = $result->fetch_all(MYSQLI_ASSOC);
    
            // Mapear el campo BLOB a base64
            foreach ($niveles as &$nivel) {
                if (!is_null($nivel['imagen_curso'])) {
                    $nivel['imagen_curso'] = base64_encode($nivel['imagen_curso']);
                }
            }
    
            return $niveles; // Devuelve un arreglo de objetos con el campo BLOB mapeado
        } else {
            return false; // No se encontraron niveles
        }
    }
    
}
