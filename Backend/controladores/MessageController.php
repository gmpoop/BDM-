<?php

class MessageController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getMessages($userId, $personaId) {
        $query = "SELECT m.mensaje, m.fecha_envio,
                         CASE 
                             WHEN m.remitente_id = ? THEN 'Tú'
                             ELSE u.nombre_completo
                         END AS usuario_nombre
                  FROM mensajes m
                  JOIN usuarios u ON m.remitente_id = u.id
                  WHERE (m.remitente_id = ? AND m.destinatario_id = ?)
                     OR (m.remitente_id = ? AND m.destinatario_id = ?)
                  ORDER BY m.fecha_envio";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("iiii", $userId, $userId, $personaId, $personaId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $messages = [];
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }

        echo json_encode($messages);
    }
}
?>
