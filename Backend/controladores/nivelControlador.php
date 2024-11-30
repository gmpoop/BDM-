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

    public function Create(){
        $data = json_decode(file_get_contents("php://input"));

        $this->nivel->curso_id = $_POST['curso_id'];
        $this->nivel->titulo = $_POST['titulo'];
        $this->nivel->contenido = $_POST['contenido'];
        $this->nivel->video = $_POST['video'];

        
        if ($this->nivel->CrearNivel()) {
            http_response_code(201);
            echo json_encode(array("message" => "Nivel creado con éxito."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "No se pudo crear el nivel."));
        }
    }

    public function GetNivel(){
        $data = json_decode(file_get_contents("php://input"));
    
        $this->nivel->id = $data->id;
    
        $result = $this->nivel->ObtenerNivel($data->id);
        if ($result) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Nivel no encontrado."));
        }
    }
    

    public function GetNiveles(){
        $data = json_decode(file_get_contents("php://input"));
    
        $result = $this->nivel->ObtenerNiveles($data->id);

    
        if ($result) {
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Niveles no encontrados."));
        }
    }
    
    


}