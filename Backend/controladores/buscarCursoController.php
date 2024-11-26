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
        $resultados = $this->cursoModel->buscarCursos($titulo, $categoria_id, $instructor_id, $estado, $start_date, $end_date);

        // Devolver resultados en formato JSON
        if ($resultados) {
            echo json_encode($resultados);
        } else {
            echo json_encode(["message" => "No se encontraron cursos con los filtros aplicados."]);
        }
    }
}
?>
