<?php
class ReportesController {
    private $reporte;

    public function __construct($db) {
        $this->reporte = new Reporte($db);
    }

    // Obtener todos los reportes
    public function getAllReportes() {
        $stmt = $this->reporte->getAllReportes();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $reportes_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reporte_item = array(
                    "correo" => $row['correo'],
                    "nombre_completo" => $row['nombre_completo'],
                    "fecha_ingreso" => $row['fecha_ingreso'],
                    "cursos_inscritos" => $row['cursos_inscritos'],
                    "porcentaje_cursos_terminados" => $row['porcentaje_cursos_terminados']
                );
                array_push($reportes_arr, $reporte_item);
            }

            http_response_code(200);
            echo json_encode($reportes_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron reportes."));
        }
    }
}
