<?php
class ReportesController
{
    private $reporte;

    public function __construct($db)
    {
        $this->reporte = new Reporte($db);
    }

    // Obtener todos los reportes
    public function getReporteEstudiante()
    {
        $result = $this->reporte->getReporteEstudiante();

        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            $reportes_arr = array();
            while ($row = $result->fetch_assoc()) {
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

    public function getReporteTutores()
    {
        $result = $this->reporte->getReporteTutores();

        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            $reportes_arr = array();
            while ($row = $result->fetch_assoc()) {
                $reporte_item = array(
                    "correo" => $row['correo'],
                    "nombre_completo" => $row['nombre_completo'],
                    "ultimo_cambio" => $row['ultimo_cambio'],
                    "fecha_ingreso" => $row['fecha_ingreso'],
                    "cursos_ofrecidos" => $row['cursos_ofrecidos'],
                    "total_ganancias" => $row['total_ganancias']
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

    public function getReportesTotales()
    {
        $result = $this->reporte->obtenerReportesTotales();

        // Verificar si hay resultados
        if ($result->num_rows > 0) {
            $reportes_arr = array();
            while ($row = $result->fetch_assoc()) {
                $reporte_item = array(
                    "reportes_estudiantes" => $row['reportes_estudiantes'],
                    "reportes_tutores" => $row['reportes_tutores'],
                    "reportes_totales" => $row['reportes_totales']
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

    public function getReporteEstudiantePorId($id)
    {
        $result = $this->reporte->getReporteEstudiantePorId($id);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $reporte_item = array(
                "correo" => $row['correo'],
                "nombre_completo" => $row['nombre_completo'],
                "fecha_ingreso" => $row['fecha_ingreso'],
                "cursos_inscritos" => $row['cursos_inscritos'],
                "porcentaje_cursos_terminados" => $row['porcentaje_cursos_terminados']
            );

            http_response_code(200);
            echo json_encode($reporte_item);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontró el reporte del estudiante."));
        }
    }

    public function getReporteTutorPorId($id)
    {
        $result = $this->reporte->getReporteTutorPorId($id);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $reporte_item = array(
                "correo" => $row['correo'],
                "nombre_completo" => $row['nombre_completo'],
                "ultimo_cambio" => $row['ultimo_cambio'],
                "fecha_ingreso" => $row['fecha_ingreso'],
                "cursos_ofrecidos" => $row['cursos_ofrecidos'],
                "total_ganancias" => $row['total_ganancias']
            );

            http_response_code(200);
            echo json_encode($reporte_item);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontró el reporte del tutor."));
        }
    }

}