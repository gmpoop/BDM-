<?php
include 'conexion.php';

// Realizar la consulta
$sql = "SELECT id, nombre, tipo FROM Categorias";
$result = $conn->query($sql);

// Verificar si hay resultadoss
if ($result->num_rows > 0) {

    // Crear tarjetas HTML para mostrar los resultados
    while($row = $result->fetch_assoc()) {
        echo "<div class='max-w-sm rounded overflow-hidden shadow-lg bg-white m-4'>
                <img class='w-full' src='https://via.placeholder.com/400x200' alt='Image'>
                <div class='px-6 py-4'>
                    <div class='font-bold text-xl mb-2'>" . $row["nombre"] . "</div>
                    <p class='text-gray-700 text-base'>
                        Aquí va una breve descripción de la tarjeta. Puedes personalizar este texto según tus necesidades.
                    </p>
                </div>
                <div class='px-6 pt-4 pb-2'>
                    <span class='inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2'>#" . $row["tipo"] . "</span>
                </div>
              </div>";
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>
