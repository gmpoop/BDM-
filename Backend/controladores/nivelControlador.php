<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
;

class NivelControlador
{
    private $db;
    private $nivel;

    public function __construct($db)
    {
        $this->db = $db;
        $this->nivel = new Nivel($db);
    }

    public function GetNivel(){
        $data = json_decode(file_get_contents("php://input"));

        $this->nivel->id = $data->id;

        $result  = $this->nivel->ObtenerNivel($data->id);
        if ($result) {
            http_response_code(response_code: 200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Nivel no encontrado."));
        }

    }

    public function GetNiveles(){
        $data = json_decode(file_get_contents("php://input"));

        $this->nivel->id = $data->id;

        $result  = $this->nivel->ObtenerNiveles($data->id);
        if ($result) {
            http_response_code(response_code: 200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Niveles no encontrados."));
        }

    }


}