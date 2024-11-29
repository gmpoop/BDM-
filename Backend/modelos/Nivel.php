
<?php
class Nivel {
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
    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /////
    // Método para obtener los datos del administrador
    /////

    public function CrearNivel() {
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
    

        public function ObtenerNivel($id) {
            $query = "
                    SELECT * FROM {$this->table_name} WHERE id = ?;
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);  // Usa el marcador de posición y enlaza el parámetro

            $stmt->execute();
            
            return $stmt; // Retorna false si no se encontró un administrador activo
    }

        public function ObtenerNiveles($id) {
            $query = "
                    SELECT * FROM {$this->table_name} WHERE curso_id = ?;
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);  // Usa el marcador de posición y enlaza el parámetro

            $stmt->execute();
            $result = $stmt->get_result();
            
            return $result; // Retorna false si no se encontró un administrador activo
    }
    
}
