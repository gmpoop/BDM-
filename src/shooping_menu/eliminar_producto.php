<?php
include 'C:\xampp\htdocs\BDM-\src\DBOContext\conection.php';

// Obtener el ID del producto desde la solicitud POST
$id = $_POST['id'] ?? null;

if ($id) {
    // Preparar y ejecutar la consulta de eliminación
    $stmt = $conn->prepare("DELETE FROM Carrito WHERE id = ?");
    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
}

$conn->close();
?>
