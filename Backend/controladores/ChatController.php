<?php

class ChatController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getChats($userId) {
        $query = "SELECT DISTINCT u.id, u.nombre_completo, u.foto, u.ruta_foto
                  FROM usuarios u
                  JOIN mensajes m ON (m.remitente_id = u.id OR m.destinatario_id = u.id)
                  WHERE m.remitente_id = ? OR m.destinatario_id = ?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $chats = [];
        while ($row = $result->fetch_assoc()) {
            $chats[] = $row;
        }

        echo json_encode($chats);
    }
}
?>
