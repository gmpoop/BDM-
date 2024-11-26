<?php
require_once '../modelos/comentario.php';

class ComentariosController {
    private $comentario;

    public function __construct($db) {
        $this->comentario = new Comentario($db);
    }

    public function getAllComentarios() {
        $stmt = $this->comentario->getAll();
        $stmt->store_result(); // Necesario para contar las filas
        $num = $stmt->num_rows;

        if ($num > 0) {
            $comentarios_arr = array();
            $stmt->bind_result($id, $curso_id, $usuario_id, $comentario, $calificacion, $fecha_creacion, $fecha_eliminacion, $causa_eliminacion);

            while ($stmt->fetch()) {
                $comentario_item = array(
                    "id" => $id,
                    "curso_id" => $curso_id,
                    "usuario_id" => $usuario_id,
                    "comentario" => $comentario,
                    "calificacion" => $calificacion,
                    "fecha_creacion" => $fecha_creacion,
                    "fecha_eliminacion" => $fecha_eliminacion,
                    "causa_eliminacion" => $causa_eliminacion
                );
                array_push($comentarios_arr, $comentario_item);
            }

            http_response_code(200);
            echo json_encode($comentarios_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron comentarios."));
        }
    }

    public function getComentario($id) {
        $stmt = $this->comentario->getOne($id);
        $stmt->store_result();
        $num = $stmt->num_rows;

        if ($num > 0) {
            $stmt->bind_result($id, $curso_id, $usuario_id, $comentario, $calificacion, $fecha_creacion, $fecha_eliminacion, $causa_eliminacion);
            $stmt->fetch();

            $comentario_data = array(
                "id" => $id,
                "curso_id" => $curso_id,
                "usuario_id" => $usuario_id,
                "comentario" => $comentario,
                "calificacion" => $calificacion,
                "fecha_creacion" => $fecha_creacion,
                "fecha_eliminacion" => $fecha_eliminacion,
                "causa_eliminacion" => $causa_eliminacion
            );

            http_response_code(200);
            echo json_encode($comentario_data);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Comentario no encontrado."));
        }
    }
    public function getComentariosPorCurso($curso_id) {
        // Llamar a la función getperCurso de la clase Comentario
        $comentarios = $this->comentario->getperCurso($curso_id);
    
        // Verificar si se obtuvieron resultados
        if (!empty($comentarios)) {
            // Si hay comentarios, devolver la respuesta con código 200
            http_response_code(200);
            echo json_encode($comentarios);
        } else {
            // Si no hay comentarios, devolver un error 404
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron comentarios para el curso especificado."));
        }
    }
    

    public function createComentario() {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->curso_id) && !empty($data->usuario_id) && !empty($data->comentario) && isset($data->calificacion)) {
            $this->comentario->curso_id = $data->curso_id;
            $this->comentario->usuario_id = $data->usuario_id;
            $this->comentario->comentario = $data->comentario;
            $this->comentario->calificacion = $data->calificacion;

            if ($this->comentario->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Comentario creado con éxito."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al crear el comentario."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos."));
        }
    }

    public function updateComentario($id) {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->curso_id) && !empty($data->usuario_id) && !empty($data->comentario) && isset($data->calificacion)) {
            $this->comentario->id = $id;
            $this->comentario->curso_id = $data->curso_id;
            $this->comentario->usuario_id = $data->usuario_id;
            $this->comentario->comentario = $data->comentario;
            $this->comentario->calificacion = $data->calificacion;

            if ($this->comentario->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Comentario actualizado con éxito."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al actualizar el comentario."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos."));
        }
    }

    public function deleteComentario($id) {
        $this->comentario->id = $id;

        if ($this->comentario->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Comentario eliminado con éxito."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al eliminar el comentario."));
        }
    }
}
