
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
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            if ($data) {
                // Asignar valores a las propiedades
                $this->id = $data['id'];
                $this->curso_id = $data['curso_id'];
                $this->titulo = $data['titulo'];
                $this->contenido = $data['contenido'];
                $this->video = $data['video'] ?? 'https://i.pinimg.com/474x/38/6c/c3/386cc3135db08ffff59778f34f056199.jpg';
                $this->estado = $data['estado'];  // Ruta predeterminada
            
                return $data; // También retorna el arreglo asociativo si se necesita
            }

            return false; // Retorna false si no se encontró un administrador activo
    }

        public function ObtenerNiveles($id) {
            $query = "
                    SELECT * FROM {$this->table_name} WHERE curso_id = ?;
            ";

            $stmt = $this->db->prepare($query);
            $stmt->bind_param("i", $id);  // Usa el marcador de posición y enlaza el parámetro

            $stmt->execute();
            $result = $stmt->get_result();
            $data = $result->fetch_assoc();

            if ($data) {
                // Asignar valores a las propiedades
                $this->id = $data['id'];
                $this->curso_id = $data['curso_id'];
                $this->titulo = $data['titulo'];
                $this->contenido = $data['contenido'];
                $this->video = $data['video'] ?? 'https://i.pinimg.com/474x/38/6c/c3/386cc3135db08ffff59778f34f056199.jpg';
                $this->estado = $data['estado'];  // Ruta predeterminada
            
                return $data; // También retorna el arreglo asociativo si se necesita
            }

            return false; // Retorna false si no se encontró un administrador activo
    }
    
}
