<?php
require_once '../modelos/categoria.php';

class CategoriasController {
    private $categoria;

    public function __construct($db) {
        $this->categoria = new Categoria($db);
    }

    public function getAllCategorias() {
        $stmt = $this->categoria->getAll();
        $stmt->store_result(); // Necesario para contar las filas
        $num = $stmt->num_rows;

        if ($num > 0) {
            $categorias_arr = array();
            $stmt->bind_result($id, $nombre, $descripcion, $usuario_creador_id, $fecha_creacion); // Asegúrate de que los campos coincidan

            while ($stmt->fetch()) {
                $categoria_item = array(
                    "id" => $id,
                    "nombre" => $nombre,
                    "descripcion" => $descripcion,
                    "usuario_creador_id" => $usuario_creador_id,
                    "fecha_creacion" => $fecha_creacion
                );
                array_push($categorias_arr, $categoria_item);
            }

            http_response_code(200);
            echo json_encode($categorias_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron categorías."));
        }
    }

    public function getCategoria($id) {
        $stmt = $this->categoria->getOne($id);
        $stmt->store_result();
        $num = $stmt->num_rows;

        if ($num > 0) {
            $stmt->bind_result($id, $nombre, $descripcion, $usuario_creador_id, $fecha_creacion);
            $stmt->fetch();

            $categoria = array(
                "id" => $id,
                "nombre" => $nombre,
                "descripcion" => $descripcion,
                "usuario_creador_id" => $usuario_creador_id,
                "fecha_creacion" => $fecha_creacion
            );

            http_response_code(200);
            echo json_encode($categoria);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Categoría no encontrada."));
        }
    }

    public function createCategoria() {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->nombre) && !empty($data->usuario_creador_id)) {
            $this->categoria->nombre = $data->nombre;
            $this->categoria->descripcion = $data->descripcion;
            $this->categoria->usuario_creador_id = $data->usuario_creador_id;

            if ($this->categoria->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Categoría creada con éxito."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al crear la categoría."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos."));
        }
    }

    public function updateCategoria($id) {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->nombre) && !empty($data->descripcion)) {
            $this->categoria->id = $id;
            $this->categoria->nombre = $data->nombre;
            $this->categoria->descripcion = $data->descripcion;

            if ($this->categoria->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Categoría actualizada con éxito."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al actualizar la categoría."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos."));
        }
    }

    public function deleteCategoria($id) {
        $this->categoria->id = $id;

        if ($this->categoria->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Categoría eliminada con éxito."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al eliminar la categoría."));
        }
    }
}
