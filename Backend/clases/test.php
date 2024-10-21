<?php
include 'Database.php';

// Realizar la consulta
$sql = "SELECT id, nombre FROM Categorias";
$result = $conn->query($sql);

// Verificar si la consulta se ejecutó correctamente
if ($result === false) {
    die("Error en la consulta: " . $conn->error);
}

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Crear tarjetas HTML para mostrar los resultados
    while($row = $result->fetch_assoc()) {
        echo "<div class='max-w-sm rounded overflow-hidden shadow-lg bg-white m-4'>
                <img class='w-full' src='https://via.placeholder.com/400x200' alt='Image'>
                <div class='px-6 py-4'>
                    <div class='font-bold text-xl mb-2'>" . htmlspecialchars($row["nombre"]) . "</div>
                    <p class='text-gray-700 text-base'>
                        Aquí va una breve descripción de la tarjeta. Puedes personalizar este texto según tus necesidades.
                    </p>
                </div>
              </div>";
    }
} else {
    echo "<p class='text-gray-700'>No se encontraron categorías.</p>";
}

$conn->close();
?>