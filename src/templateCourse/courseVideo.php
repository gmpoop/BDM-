<?php
include 'C:\xampp\htdocs\BDM-\src\DBOContext\conection.php';

$idCurso = $_GET['idCurso'] ?? null;
$idNivel = $_GET['idNivel'] ?? null;
$resultNivel = null;
$resultCurso = null;
$continue = false;

$sql = "SELECT id, nivel, idCurso, descripcion_corta, descripcion_larga, video_url
FROM Nivel WHERE nivel = ? AND idCurso = ?";

$stmt = $conn->prepare($sql);

if ($stmt) {

    $stmt->bind_param("ii", $idNivel, $idCurso);
    $stmt->execute();

    $resultNivel = $stmt->get_result();
}

$sql = "SELECT id, nivel, idCurso, descripcion_corta
FROM Nivel WHERE idCurso = ?";

$stmt = $conn->prepare($sql);

if ($stmt) {
    // Enlazar el parámetro (en este caso, "i" indica que el parámetro es un entero)
    $stmt->bind_param("i", $idCurso);
    $stmt->execute();

    // Obtener el resultado
    $resultCurso = $stmt->get_result();
}

// Verificar si hay resultados
    if ($resultNivel && $resultNivel->num_rows > 0) {
        $continue = true;
    }
    else {
        $continue = false;
    }

    if ($resultNivel === false) {
        // Manejar el error de consulta
        die('Error en la consulta SQL: ' . $conn->error);
    }

    if ($resultCurso && $resultCurso->num_rows > 0) {
        $continue = true; 
    }else
    {
        $continue = false;
    }

    // Crear instancias de Carrito y añadirlas al array
    while ($rowNivel = $resultNivel->fetch_assoc()) {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Video</title>
            <link href='../output.css' rel='stylesheet'>
            <link href='https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css' rel='stylesheet'>
            <!-- Incluir SweetAlert -->
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body id='body' class='w-full mx-auto bg-gray-100 text-gray-800 h-screen'>
            <div class=' bg-white rounded-xl shadow-md p-6 flex h-full w-auto '>
                <videoSection class='flex flex-col items-start gap-2'>
                    <div>
                        <img src='https://via.placeholder.com/850x500' alt='Curso 1' class='rounded-lg mb-4 min-w-[500px] lg:min-w-[850px]'>
                    </div>
                    <div class='menuVideo'>
                        <ul class='flex gap-5 font-bold text-gray-500'>
                            <li class='element-list-video_menu'>
                                <button id='btnOverview' class='focus:outline-none focus:text-gray-900'>Resumen</button>
                            </li>
                            <li class='element-list-video_menu'>
                                <button id='btnComments' class='focus:outline-none focus:text-gray-900'>Comentarios</button>
                            </li>
                        </ul>
                    </div>
                    
                    <!-- Overview Section -->
                <div id='overview' class='w-auto flex flex-col gap-2'>
                        <div class='flex items-center gap-3'>
                            <h3 class='text-lg font-bold text-gray-900 '>Nivel ".$rowNivel["nivel"]."</h3>
                            <span>-</span>
                            <h3 class='text-lg font-bold text-gray-900'>".$rowNivel["descripcion_corta"]."</h3>
                        </div>
                        <p id='description'>".$rowNivel["descripcion_larga"]."</p>
                    </div>
                                
                    <!-- Comments Section -->
                    <div id='comments' class='hidden'>
                        <div class='bg-gray-100 p-4 rounded-lg mb-4'>
                            <div class='flex items-center mb-2'>
                                <div class='bg-[#4821ea] w-10 h-10 rounded-full flex items-center justify-center text-white font-bold mr-3'>JD</div>
                                <h3 class='text-lg font-semibold text-gray-700'>Añadir comentario</h3>
                            </div>
                            <p class='text-gray-600'>Este curso ha sido realmente útil. Me ha ayudado a entender conceptos que antes no comprendía. ¡Recomendado!</p>
                            <div class='text-sm text-gray-500 mt-2'>Hace 2 horas</div>
                        </div>
                        <div class='bg-gray-100 p-4 rounded-lg mb-4'>
                            <div class='flex items-center mb-2'>
                                <div class='bg-[#4821ea] w-10 h-10 rounded-full flex items-center justify-center text-white font-bold mr-3'>JD</div>
                                <h3 class='text-lg font-semibold text-gray-700'>Juan Díaz</h3>
                            </div>
                            <p class='text-gray-600'>Este curso ha sido realmente útil. Me ha ayudado a entender conceptos que antes no comprendía. ¡Recomendado!</p>
                            <div class='text-sm text-gray-500 mt-2'>Hace 2 horas</div>
                        </div>
                        <div class='bg-gray-100 p-4 rounded-lg mb-4'>
                            <div class='flex items-center mb-2'>
                                <div class='bg-[#4821ea] w-10 h-10 rounded-full flex items-center justify-center text-white font-bold mr-3'>JD</div>
                                <h3 class='text-lg font-semibold text-gray-700'>Juan Díaz</h3>
                            </div>
                            <p class='text-gray-600'>Este curso ha sido realmente útil. Me ha ayudado a entender conceptos que antes no comprendía. ¡Recomendado!</p>
                            <div class='text-sm text-gray-500 mt-2'>Hace 2 horas</div>
                        </div>
                    </div>
                </videoSection>
                <aside class='mx-5 divide-y divide-gray-300 w-full '>
            <h1 class='text-4xl font-bold text-gray-900 mb-2'>Niveles</h1>
            <ul class='max-h-[500px] divide-y divide-gray-300 bg-white rounded-xl shadow-md p-6 overflow-y-auto w-full flex flex-col items-center'>";
                while ($rowCurso = $resultCurso->fetch_assoc()) {
                    echo "
                    <li class='w-full min-w-[250px] flex justify-between items-center p-4 transition-transform duration-300 ease-in-out hover:scale-110 hover:shadow-2xl hover:bg-gray-100 cursor-pointer' onClick='verNivel(".$rowCurso["idCurso"].", ".$rowCurso["nivel"].")'>
                        <div class='flex justify-between items-center gap-5'>
                            <img src='https://via.placeholder.com/70x50' alt='Curso 1' class='rounded-lg'>
                        <p class='text-gray-500'>".$rowCurso["nivel"].". <span>".$rowCurso["descripcion_corta"]."</span></p></div>  
                    </li>
                    ";
                } echo"
            </ul>
        </aside>
        </div>

        </body>
        </html>
        ";  
    }

$conn->close();
?>

<script>

    document.addEventListener('DOMContentLoaded', function () {

        const listElement = document.getElementById('list');

    });

    const btnOverview = document.getElementById("btnOverview");
    const btnComments = document.getElementById("btnComments");
    const overviewSection = document.getElementById("overview");
    const commentsSection = document.getElementById("comments");
    const body = document.getElementById("body");

    // Función para mostrar la sección de "Resumen" y ocultar "Comentarios"
    btnOverview.addEventListener("click", () => {
        overviewSection.classList.remove("hidden");
        commentsSection.classList.add("hidden");
        body.classList.add("h-screen");
        body.classList.remove("h-auto");
    });

    // Función para mostrar la sección de "Comentarios" y ocultar "Resumen"
    btnComments.addEventListener("click", () => {
        commentsSection.classList.remove("hidden");
        body.classList.remove("h-screen");
        body.classList.add("h-auto");
        overviewSection.classList.add("hidden");
    }); 


    function verNivel(cursoId, nivelId) {

        window.location.href = "/BDM-/src/templateCourse/courseVideo.php?idCurso=" + encodeURIComponent(cursoId) + "&idNivel=" + encodeURIComponent(nivelId);

    }
</script>
