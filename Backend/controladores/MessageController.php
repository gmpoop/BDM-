<?php

class MessageController
{
    private $conn;
    private $message;

    // Constructor con la conexión a la base de datos
    public function __construct($db)
    {
        $this->conn = $db;
        $this->message = new Message($db); // Inicializamos el modelo Message
    }

    // Método para crear un nuevo mensaje
    public function create()
    {
        // Obtener datos de la solicitud POST
        $data = json_decode(file_get_contents("php://input"));

        // Verificar si los datos necesarios están presentes
        if (isset($data->remitente_id) && isset($data->destinatario_id) && isset($data->mensaje) && isset($data->chat_id)) {

            // Asignar los datos al modelo
            $this->message->remitente_id = $data->remitente_id;
            $this->message->destinatario_id = $data->destinatario_id;
            $this->message->mensaje = $data->mensaje;
            $this->message->chat_id = $data->chat_id;

            // Llamar al método para crear el mensaje
            if ($this->message->create()) {
                echo json_encode(array("message" => "Mensaje creado correctamente."));
            } else {
                echo json_encode(array("message" => "Error al crear el mensaje."));
            }
        } else {
            echo json_encode(array("message" => "Faltan parámetros en la solicitud."));
        }
    }

    // Método para obtener los datos del remitente
    public function getDatosRemitente($id)
    {
        $result = $this->message->getDatosRemitente($id);

        // Si hay resultados, devolverlos en JSON
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            echo json_encode($data);
        } else {
            echo json_encode(array("message" => "No se encontraron datos para el id_curso: " . $id));
        }
    }

    // Método para obtener los mensajes del chat
    public function getDatosChat($id)
    {
        $result = $this->message->getDatosChat($id);

        // Si hay resultados, devolverlos en JSON
        if ($result->num_rows > 0) {
            $chats = array();
            while ($row = $result->fetch_assoc()) {
                $chats[] = $row;
            }
            echo json_encode($chats);
        } else {
            echo json_encode(array("message" => "No se encontraron mensajes para el curso ID: " . $id));
        }
    }
    public function getChatData($usuario_id, $usuario_2_id, $curso_id)
    {
        $data = $this->message->getChatDataByUserAndCourse($usuario_id, $usuario_2_id, $curso_id);

        // Verificar si se obtuvieron resultados
        if ($data) {
            echo json_encode($data);  // Enviar los resultados en formato JSON
        } else {
            echo json_encode(array("message" => "No se encontraron chats para estos usuarios en este curso."));
        }
    }


    // Función para obtener los mensajes de un chat específico
    public function obtenerMensajes($chat_id)
    {
        // Llamar a la función del modelo para obtener los mensajes del chat
        $data = $this->message->obtenerTodosMensajes($chat_id);

        // Verificar si hay mensajes
        if ($data) {
            echo json_encode($data);  // Enviar los resultados en formato JSON
        } else {
            echo json_encode(array("message" => "No se encontraron chats para este usuario y curso."));
        }
    }

}
?>