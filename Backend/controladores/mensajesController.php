<?php
require_once '../modelos/mensaje.php';

class MensajesController {
    private $mensaje;

    public function __construct($db) {
        $this->mensaje = new Mensaje($db);
    }

    public function getAllMensajes() {
        $stmt = $this->mensaje->getAll();
        $stmt->store_result(); // Necesario para contar las filas
        $num = $stmt->num_rows;

        if ($num > 0) {
            $mensajes_arr = array();
            $stmt->bind_result($id, $remitente_id, $destinatario_id, $mensaje, $fecha_envio); // Asegúrate de que los campos coincidan

            while ($stmt->fetch()) {
                $mensaje_item = array(
                    "id" => $id,
                    "remitente_id" => $remitente_id,
                    "destinatario_id" => $destinatario_id,
                    "mensaje" => $mensaje,
                    "fecha_envio" => $fecha_envio
                );
                array_push($mensajes_arr, $mensaje_item);
            }

            http_response_code(200);
            echo json_encode($mensajes_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron mensajes."));
        }
    }

    public function getMensaje($id) {
        $stmt = $this->mensaje->getOne($id);
        $stmt->store_result();
        $num = $stmt->num_rows;

        if ($num > 0) {
            $stmt->bind_result($id, $remitente_id, $destinatario_id, $mensaje, $fecha_envio);
            $stmt->fetch();

            $mensaje = array(
                "id" => $id,
                "remitente_id" => $remitente_id,
                "destinatario_id" => $destinatario_id,
                "mensaje" => $mensaje,
                "fecha_envio" => $fecha_envio
            );

            http_response_code(200);
            echo json_encode($mensaje);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Mensaje no encontrado."));
        }
    }

    public function createMensaje() {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->remitente_id) && !empty($data->destinatario_id) && !empty($data->mensaje)) {
            $this->mensaje->remitente_id = $data->remitente_id;
            $this->mensaje->destinatario_id = $data->destinatario_id;
            $this->mensaje->mensaje = $data->mensaje;

            if ($this->mensaje->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Mensaje creado con éxito."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al crear el mensaje."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos."));
        }
    }
    public function getConversation($remitente_id, $destinatario_id) {
        // Llamar a la función getConversation de la clase Mensaje
        $mensajes = $this->mensaje->getConversation($remitente_id, $destinatario_id);
    
        // Verificar si se obtuvieron resultados
        if (!empty($mensajes)) {
            // Si hay mensajes, devolver la respuesta con código 200
            http_response_code(200);
            echo json_encode($mensajes);
        } else {
            // Si no hay mensajes, devolver un error 404
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron mensajes para la conversación especificada."));
        }
    }
    

    public function updateMensaje($id) {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->remitente_id) && !empty($data->destinatario_id) && !empty($data->mensaje)) {
            $this->mensaje->id = $id;
            $this->mensaje->remitente_id = $data->remitente_id;
            $this->mensaje->destinatario_id = $data->destinatario_id;
            $this->mensaje->mensaje = $data->mensaje;

            if ($this->mensaje->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Mensaje actualizado con éxito."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Error al actualizar el mensaje."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Datos incompletos."));
        }
    }

    public function deleteMensaje($id) {
        $this->mensaje->id = $id;

        if ($this->mensaje->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Mensaje eliminado con éxito."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Error al eliminar el mensaje."));
        }
    }
}
