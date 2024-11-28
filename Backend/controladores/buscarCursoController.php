<?php
require_once '../modelos/buscarCurso.php';

class buscarCursoController {
    private $db;
    private $cursoModel;

    public function __construct($db) {
        $this->db = $db;
        $this->cursoModel = new buscarCursos($db);
    }

    // Método para manejar la búsqueda con filtros
    public function buscarCursos() {
        // Obtener parámetros de la solicitud (GET)
        $titulo = isset($_GET['titulo']) ? $_GET['titulo'] : null;
        $categoria_id = isset($_GET['categoria_id']) ? $_GET['categoria_id'] : null;
        $instructor_id = isset($_GET['instructor_id']) ? $_GET['instructor_id'] : null;
        $estado = isset($_GET['estado']) ? $_GET['estado'] : null;
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : null;
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : null;

        // Realizar la búsqueda con los parámetros obtenidos
        $stmt = $this->cursoModel->buscarCursos($titulo, $categoria_id, $instructor_id, $estado, $start_date, $end_date);

        // Devolver resultados en formato JSON
        if ($stmt) {
            $stmt->store_result(); // Asegúrate de almacenar el resultado para obtener el número de filas
            $num = $stmt->num_rows;

            if ($num > 0) {
                $cursos_arr = array();
                $cursos_arr["records"] = array();

                $stmt->bind_result($categoria_id, $nombre_categoria, $id_curso, $titulo, $descripcion, $imagen, $costo);
                while ($stmt->fetch()) {
                    $imagenBase64 = base64_encode($imagen);

                    // Crear una URL de datos para la imagen
                    $imagenSrc = 'data:image/jpeg;base64,' . $imagenBase64;

                    $curso_item = array(
                        "categoria_id" => $categoria_id,
                        "nombre_categoria" => $nombre_categoria,
                        "id_curso" => $id_curso,
                        "titulo" => $titulo,
                        "descripcion" => $descripcion,
                        "imagen" => $imagenSrc,
                        "costo" => $costo
                    );
                    array_push($cursos_arr["records"], $curso_item);
                }

                http_response_code(200);
                echo json_encode($cursos_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No se encontraron cursos."));
            }
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Error al obtener los cursos."));
        }
    }
}
?>
