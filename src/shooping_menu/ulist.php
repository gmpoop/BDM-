<?php
include '..\src\DBOContext\conection.php';

// Realizar la consulta
$sql = "SELECT id, nombre, categoria, nivel, precio FROM Carrito";
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {

    echo "Resultado" . $result . ; 

    // Crear tarjetas HTML para mostrar los resultados
    while($row = $result->fetch_assoc()) {
        echo "  <ul class='divide-y divide-gray-300'>
                        <!-- Elemento del Carrito -->
                        <li class='flex justify-between items-center py-4'>
                            <div class='flex flex-col items-start'>
                                <h3 class='text-lg font-bold text-gray-700'>Curso de Desarrollo Web Completo</h3>
                                <p class='text-gray-500'>Nivel: Intermedio</p>
                                <button id='' class='mt-4 bg-[#4821ea] text-white py-2 px-4 rounded hover:bg-[#3d1bc8]' onclick='agregarAlCarrito()'>
                                    Ver Curso
                                </button>                                
                            </div>
                            <p class='text-lg font-bold text-[#4821ea]'>$<span class='price'>49.99</span></p>
                            <button class='bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600' onclick='eliminarDelCarrito()'>
                                Eliminar
                            </button>
                        </li>
                    </ul>";
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>
