<?php
class Curso {
    private $conn;

    public $id;
    public $titulo;
    public $descripcion;
    public $imagen;
    public $ruta_imagen;
    public $costo;
    public $estado;
    public $categoria_id;
    public $instructor_id;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Crear una nueva categoría
    public function create() {
        $query = "CALL sp_insertar_curso(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
    
        // Sanitizar
        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
        $this->ruta_imagen = htmlspecialchars(strip_tags($this->ruta_imagen));
        $this->costo = htmlspecialchars(strip_tags($this->costo));
        $this->categoria_id = htmlspecialchars(strip_tags($this->categoria_id));
        $this->instructor_id = htmlspecialchars(strip_tags($this->instructor_id));
    
        // Bind
        $stmt->bind_param('ssssiii', $this->titulo, $this->descripcion, $this->imagen, $this->ruta_imagen, $this->costo, $this->categoria_id, $this->instructor_id);
    
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    // Obtener todas las categorías
    public function getAll() {
        $query = "SELECT * FROM cursos"; // No requiere un SP aquí
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function GetAllByCourse() {
        $query = "SELECT cur.categoria_id, cat.nombre as nombre_categoria, cur.id as id_curso, cur.titulo, cur.descripcion, cur.imagen, cur.costo 
                  FROM cursos cur 
                  JOIN categorias cat ON cur.categoria_id = cat.id 
                  WHERE cur.estado LIKE '%activo%'";
    
        $stmt = $this->conn->prepare($query);
    
        if ($stmt) {
            // Ejecutar la declaración
            $stmt->execute();
    
            // Retornar el statement
            return $stmt;
        } else {
            // Manejar el error de preparación
            echo "Error en la preparación de la declaración: " . $this->conn->error;
            return false;
        }
    }
    
    public function getCoursesDetails($curso_id) {
        $query = "SELECT cur.id as idCurso, cur.titulo as titulo_curso, cur.descripcion, cur.imagen, 
                  us.id as idUsuario, us.nombre_completo, us.email, 
                  niv.id as idNivel, niv.titulo as titulo_nivel 
                  FROM cursos cur 
                  JOIN usuarios us ON cur.instructor_id = us.id 
                  JOIN niveles niv ON cur.id = niv.curso_id 
                  WHERE cur.id = ?";
    
        $stmt = $this->conn->prepare($query);
    
        if ($stmt) {
            // Enlazar parámetros para proteger contra inyecciones SQL
            $stmt->bind_param('i', $curso_id);
    
            // Ejecutar la declaración
            $stmt->execute();
            
            return $stmt;

        } else {
            // Manejar error de preparación
            echo "Error en la preparación de la declaración: " . $this->conn->error;
            return false;
        }
    }
    
    

    // Obtener una categoría por ID
    public function getOne($id) {
        $query = "SELECT * FROM cursps WHERE id = ?"; // No requiere un SP aquí
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt;
    }

    function getOneBy($title, $instructor_id) {
        // Añadir comodines para LIKE
        $title = '%' . $title . '%'; 
        $query = "SELECT * FROM cursos WHERE titulo LIKE ? AND instructor_id = ?";
    
        // Preparar la declaración
        if ($stmt = $this->conn->prepare($query)) {
            // Enlazar los parámetros a los marcadores de posición
            $stmt->bind_param('si', $title, $instructor_id);
    
            // Ejecutar la declaración
            if ($stmt->execute()) {
                // Obtener el resultado
                $result = $stmt->get_result();
    
                // Verificar si hay resultados
                if ($result->num_rows > 0) {
                    // Obtener el primer resultado
                    $course = $result->fetch_assoc();
                    return $course;
                } else {
                    return null; // No hay resultados
                }
            } else {
                echo "Error en la ejecución: " . $stmt->error;
                return false;
            }
        } else {
            echo "Error en la preparación de la declaración: " . $this->conn->error;
            return false;
        }
    }
    
    

    // Actualizar una categoría
    // public function update() {
    //     $query = "CALL sp_modificar_categoria(?, ?, ?, ?)";
    //     $stmt = $this->conn->prepare($query);

    //     // Sanitizar
    //     $this->nombre = htmlspecialchars(strip_tags($this->nombre));
    //     $this->descripcion = htmlspecialchars(strip_tags($this->descripcion));
    //     $this->usuario_creador_id = htmlspecialchars(strip_tags($this->usuario_creador_id));

    //     // Bind
    //     $stmt->bind_param('issi', $this->id, $this->nombre, $this->descripcion, $this->usuario_creador_id);

    //     if ($stmt->execute()) {
    //         return true;
    //     }
    //     return false;
    // }

    // // Eliminar una categoría
    // public function delete() {
    //     $query = "CALL sp_borrar_categoria(?)";
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->bind_param('i', $this->id);

    //     if ($stmt->execute()) {
    //         return true;
    //     }
    //     return false;
    // }
}
