<?php
require_once 'Database.php';
require_once 'admin.php';

// Crear conexión
$conn = (new Database())->conectar();

// Instanciar el modelo
$adminModel = new AdminModel($conn);

// Obtener datos del administrador
if ($adminModel->obtenerAdministrador()) {
    $nombre = htmlspecialchars($adminModel->nombre_completo);
    $email = htmlspecialchars($adminModel->email);
    $avatar = htmlspecialchars($adminModel->ruta_foto);
} else {
    // Valores predeterminados si no hay administrador
    $nombre = "Administrador";
    $email = "admin@example.com";
    $avatar = "https://i.pinimg.com/474x/38/6c/c3/386cc3135db08ffff59778f34f056199.jpg";
}
