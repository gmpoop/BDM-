<?php
include 'C:\xampp\htdocs\BDM-\src\DBOContext\conection.php';
include 'C:\xampp\htdocs\BDM-\src\shooping_menu\Clases\Carrito.php'; // Incluir la definición de la clase

// Inicializar un array para almacenar los productos
$productos = [];

// Realizar la consulta
$sql = "SELECT id, nombre, categoria, nivel, precio, id_User FROM Carrito";
$result = $conn->query($sql);

// Verificar si hay resultados
if ($result->num_rows > 0) {
    // Crear instancias de Carrito y añadirlas al array
    while ($row = $result->fetch_assoc()) {
        $producto = new Carrito(
            $row["id"],
            $row["nombre"],
            $row["categoria"],
            $row["nivel"],
            $row["precio"],
            $row["id_User"]
        );
        
        // Añadir la instancia al array
        $productos[] = $producto;
    }
} else {
    echo "0 resultados";
}

$conn->close();

// Generar la salida HTML usando el array de productos
foreach ($productos as $producto) {
    echo "<ul class='divide-y divide-gray-300'>
            <!-- Elemento del Carrito -->
            <li class='flex justify-between items-center py-4'>
                <div class='flex flex-col items-start gap-3'>
                    <h3 class='text-lg font-bold text-gray-700'>" . htmlspecialchars($producto->nombre) . "</h3>
                    <p class='text-gray-500'>Nivel: " . htmlspecialchars($producto->nivel) . "</p>
                    <div class='bg-gray-400 rounded-lg p-2 shadow-lg flex flex-wrap max-w-[350px]'>
                        <p class='text-gray-900 text-xs font-semibold'>" . htmlspecialchars($producto->categoria) . "</p>
                    </div>
                    <button id='course' class='mt-4 bg-[#4821ea] text-white py-2 px-4 rounded hover:bg-[#3d1bc8]' onclick='verCurso(" . htmlspecialchars($producto->id) . ")'>
                        Ver Curso
                    </button>                                
                </div>
                <p class='text-lg font-bold text-[#4821ea]'>$<span class='price'>" . htmlspecialchars($producto->precio) . "</span></p>
                <button class='bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600' onclick='eliminarDelCarrito(" . htmlspecialchars($producto->id) . ")'>
                    Eliminar
                </button>
            </li>
        </ul>";
}
?>


