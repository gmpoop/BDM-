<?php
require_once '../modelos/comentario.php';

class ComentarioController {
    private $comentario;

    public function __construct($db) {
        $this->comentario = new Comentario($db);
    }

    // Obtener comentarios por curso
    public function getComentariosPorCurso($curso_id) {
        $stmt = $this->comentario->getComentariosPorCurso($curso_id);
        $stmt->store_result();
        $num = $stmt->num_rows;

        if ($num > 0) {
            $comentarios_arr = array();
            $stmt->bind_result($id_comentario, $curso_id, $usuario_nombre, $comentario, $fecha_creacion, $fecha_eliminacion, $calificacion);

            while ($stmt->fetch()) {
                $comentario_item = array(
                    "id_comentario" => $id_comentario,
                    "curso_id" => $curso_id,
                    "usuario_nombre" => $usuario_nombre,
                    "comentario" => $comentario,
                    "fecha_creacion" => $fecha_creacion,
                    "fecha_eliminacion" => $fecha_eliminacion,
                    "calificacion" => $calificacion
                );
                array_push($comentarios_arr, $comentario_item);
            }

            http_response_code(200);
            echo json_encode($comentarios_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron comentarios para este curso."));
        }
    }

    public function insertarComentario() {
        $data = json_decode(file_get_contents("php://input"));

        // Verificar que los datos necesarios estén presentes
        if (!empty($data->curso_id) && !empty($data->usuario_id) && !empty($data->comentario) && !empty($data->calificacion)) {
            // Asignar valores al modelo
            $this->comentario->curso_id = $data->curso_id;
            $this->comentario->usuario_id = $data->usuario_id;
            $this->comentario->comentario = $data->comentario;
            $this->comentario->calificacion = $data->calificacion;

            // Insertar el comentario
            if ($this->comentario->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Comentario creado con éxito."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo crear el comentario."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos."));
        }
    }
}
?>
