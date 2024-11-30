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
    public function getReportePorcurso($id) {
        if (!is_numeric($id)) {
            http_response_code(400);
            echo json_encode(array("message" => "El ID del instructor debe ser un número."));
            return;
        }

        $result = $this->reporte->getReporteCadaCurso($id);

        if (!empty($result)) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron cursos o ventas para el instructor con ID $id."));
        }
    }
    public function reportecadaCursos($id) {
        // Obtener los cursos del instructor
        $resultados = $this->reporte->reporteCursos($id);

        // Comprobar si se encontraron cursos
        if ($resultados) {
            echo json_encode($resultados);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron cursos para el instructor con ID $id."));
        }
    }
    public function getReporteInscripcion($id)
    {
        $result = $this->reporte->getReporteInscripcionesUser($id);

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
    public function getReporteCursosUsuario($id) {
        // Obtener el reporte desde el modelo
        $result = $this->reporte->getReporteInscripcionesUser($id);

        if ($result->num_rows > 0) {
            $cursos = array();

            // Obtener los cursos del usuario
            while ($row = $result->fetch_assoc()) {
                $curso_item = array(
                    "id_usuario" => $row['id_usuario'],
                    "id_curso" => $row['id_curso'],
                    "nombre_curso" => $row['nombre_curso'],
                    "progreso" => $row['progreso'],
                    "fecha_inscripcion" => $row['fecha_inscripcion'],
                    "descripcion_curso" => $row['descripcion_curso']
                );
                $cursos[] = $curso_item;
            }

            // Establecer el código de respuesta HTTP y devolver los datos en formato JSON
            http_response_code(200);
            echo json_encode($cursos);
        } else {
            // Si no se encuentran cursos para el usuario
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron cursos para este usuario."));
        }
    }
}