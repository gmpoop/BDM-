
<?php
class inscripcionesControlador {
    private $db;
    private $inscrupciones;

    public function __construct($db) {
        $this->db = $db;
        $this->inscrupciones = new Inscripciones($this->db);
    }

    public function ConseguirInscripciones(){
        $result = $this->inscrupciones->ConseguirInscripciones();
        $inscripciones = array();
        while ($row = $result->fetch_assoc()) {
            array_push($inscripciones, $row);
        }
        if ($inscripciones) {
            http_response_code(200);
            echo json_encode($inscripciones);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No se encontraron inscripciones."]);
        }
    }

    public function ConseguirInscripcion(){

        $result = $this->ObtenerValorHeader('id_inscripcion');

        $stmt = $this->inscrupciones->ConseguirInscripcionesDetalle($result);
        $result = $stmt->get_result();

        $inscripciones = array();
        while ($row = $result->fetch_assoc()) {
            array_push($inscripciones, $row);
        }
        if ($inscripciones) {
            http_response_code(200);
            echo json_encode($inscripciones);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No se encontraron inscripciones."]);
        }
    }


    public function ActualizarProgreso(){

        $data = json_decode(file_get_contents("php://input"));

        $this->inscrupciones->id = $data->id;
        $this->inscrupciones->usuario_id = $data->usuario_id;
        $this->inscrupciones->progreso = $data->progreso;
        $this->inscrupciones->niveles_completados = $data->niveles_completados;
        
        if ($this->inscrupciones->Actualizar()) {
            http_response_code(200);
            echo json_encode(["message" => "Progreso actualizado exitosamente."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "Error al actualizar el progreso."]);
        }

    }
    
    public function ObtenerValorHeader($headerName) {
        $headers = getallheaders();
        if (isset($headers[$headerName])) {
            return $headers[$headerName];
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Header no encontrado."]);
            return null;
        }
    }

}
?>