<?php
include 'C:\xampp\htdocs\BDM-\src\DBOContext\conection.php';

// Validar y procesar la solicitud de inserción
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Validar los datos recibidos
  // Recibir y procesar los datos del formulario
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $contraseña = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : '';


    // Query de inserción
    $sql = "INSERT INTO usuarios (nombre_completo, email, fecha_nacimiento, genero, contraseña, ruta_foto, estado) 
                VALUES ('$nombre', '$email', '$fecha_nacimiento', '$genero', '$contraseña', '$avatar', '$estado')";

    // Preparar el statement
    header('Content-Type: application/json');

    try {
        $sql = "INSERT INTO usuarios (email) VALUES ('$email')"; // Asegúrate de sanitizar tus entradas
        if ($conn->query($sql) === TRUE) {
            http_response_code(200);
            // Enviar JSON manualmente
            echo '{"success": true, "message": "Registro insertado correctamente."}';
        }
    } catch (mysqli_sql_exception $e) {
        http_response_code(500);
    
        // Mensaje específico para el error de duplicado
        if ($e->getCode() === 1062) { // Código de error para entrada duplicada
            echo '{"success": false, "message": "El correo electrónico ya está registrado."}';
        } else {
            // Para otros tipos de errores
            echo '{"success": false, "message": "Error:  El correo electrónico ya está registrado."}';
        }
    }
    
    // Asegúrate de cerrar la conexión si no la necesitas más
    $conn->close();
}

// }
?>


