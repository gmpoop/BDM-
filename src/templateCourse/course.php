<?php
include 'C:\xampp\htdocs\BDM-\src\DBOContext\conection.php';

$id = $_GET['id'] ?? null;
$result = null;

// Realizar la consulta
$sql = "SELECT id, nombre, categoria, cantidad_niveles, creado_por, imagen_url, descripcion_corta, descripcion_larga 
FROM Curso WHERE id = ?";

$stmt = $conn->prepare($sql);

if ($stmt) {
    // Enlazar el parámetro (en este caso, "i" indica que el parámetro es un entero)
    $stmt->bind_param("i", $id);
    $stmt->execute();

    // Obtener el resultado
    $result = $stmt->get_result();
}

// Verificar si hay resultados
if ($result && $result->num_rows > 0) {
    // Crear instancias de Carrito y añadirlas al array
    while ($row = $result->fetch_assoc()) {
        echo "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Document</title>
            <link href='../output.css' rel='stylesheet'>
            <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
            <!-- Incluir SweetAlert -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body class='w-auto mx-auto'>
    <upper class='w-full mx-auto flex flex-wrap md:flex-nowrap justify-start'>
        <aside class='m-5'>
            <div class='min-w-[300px] lg:max-w-sm bg-white rounded-xl shadow-md p-6 flex flex-col items-start'>
                <img src='". $row["imagen_url"]."' alt='Curso 1' class='min-w-[200px] rounded-lg mb-4'>
                <h3 class='text-lg font-bold text-gray-900 mb-2'>". $row["nombre"] ."</h3>
                <div class='flex items-center gap-1'>
                    <p class='text-gray-600 mb-4'>Intermedio</p> 
                </div>
                <p class='text-gray-600 mb-4'>Creado por: ". $row["creado_por"] ."</p> 
                <p class='text-gray-600 mb-4'>Cantidad de niveles: <span id='level'>". $row["cantidad_niveles"] ."</span></p>
                <a href='#' class=' bg-[#4821ea] text-white py-2 px-4 rounded-lg hover:bg-[#3415b8]'>Empezar</a>
            </div>
        </aside>
        <main class='w-full bg-white rounded-xl shadow-md p-6 flex flex-col items-start m-5'>
            <h1 class='text-4xl font-bold text-[#4821ea] mb-2'>Curso de Desarrollo Web</h1>
            <h3 class='text-lg text-gray-600 mb-4'><strong> ". $row["descripcion_corta"] ."</strong></h3>
            <p class='text-lg'>
                ". $row["descripcion_larga"] ."
            </p>
        </main>
    </upper>
    <lower class='w-full'>
        <div class='w-auto h-auto  bg-white rounded-xl shadow-md p-6 flex m-5 overflow-x-auto'>
            <ul class='divide-x divide-gray-300 flex overflow-x-auto'>";

        for ($i = 1; $i <= $row["cantidad_niveles"]; $i++) {  
            echo "<li id='list' class='min-w-[250px] flex justify-between items-center p-4 transition-transform duration-300 ease-in-out hover:scale-110 hover:shadow-2xl hover:bg-gray-100 cursor-pointer' onclick=\"verNivel(".$row["id"].", $i)\">
                    <div class='flex flex-col items-start'>
                        <img src='https://via.placeholder.com/300x200' alt='Curso Nivel $i' class='rounded-lg mb-4'>
                        <p class='text-gray-500'>Nivel: $i</p>                           
                    </div>
                  </li>";
        }

        echo "</ul>
        </div>
    </lower>
</body>";
    }
} else {
    echo "0 resultados";
}

$conn->close();
?>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const listElement = document.getElementById('list');

    });


    function verNivel(cursoId, nivelId) {

        window.location.href = "/BDM-/src/templateCourse/courseVideo.php?idCurso=" + encodeURIComponent(cursoId) + "&idNivel=" + encodeURIComponent(nivelId);

    }
</script>
