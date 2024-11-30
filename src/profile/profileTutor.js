document.addEventListener("DOMContentLoaded", () => {
    const toggleInfoBtn = document.getElementById("toggle-info-btn");
    const tutorInfo = document.getElementById("tutor-info");
    const reportesContainer = document.getElementById("reportes-container");
    const carouselContainer = document.querySelector('.carousel .flex');

    // Obtener el token JWT desde localStorage
    const tokenJWT = localStorage.getItem('jwtToken');

    if (!tokenJWT) {
        console.error("No se encontró el token JWT.");
        return;
    }

    // Decodificar el JWT
    const tokenParts = tokenJWT.split('.');
    const payloadBase64 = tokenParts[1];
    const decodedPayload = atob(payloadBase64);
    let tokenData;
    try {
        tokenData = JSON.parse(decodedPayload);
        console.log(tokenData); // Verifica que tienes los datos correctos
    } catch (error) {
        console.error("Error al decodificar el JWT:", error);
        return;
    }

    // Obtener el id del instructor desde el token decodificado
    const instructorId = tokenData.data.id;

    // Mostrar/Ocultar el contenido desplegable
    toggleInfoBtn.addEventListener("click", () => {
        tutorInfo.classList.toggle("hidden");
        const arrowIcon = document.getElementById("arrow-icon");
        arrowIcon.classList.toggle("rotate-180");
    });

    // Función para obtener los datos del API para los reportes
    async function fetchReportesPorCurso(instructorId) {
        try {
            const response = await fetch(`/BDM-/Backend/API/APIReportes.php/reporte/PorTutorPorCurso?id=${instructorId}`);
            if (!response.ok) throw new Error("Error al obtener los datos del servidor.");
            const reportes = await response.json();
            llenarReportes(reportes);
        } catch (error) {
            console.error("Error al obtener los reportes:", error);
        }
    }

    // Función para llenar los reportes
    function llenarReportes(reportes) {
        reportesContainer.innerHTML = ""; // Limpiar contenedor

        // Agrupar reportes por curso
        const reportesPorCurso = reportes.reduce((acc, reporte) => {
            acc[reporte.curso] = acc[reporte.curso] || [];
            acc[reporte.curso].push(reporte);
            return acc;
        }, {});

        // Crear un card para cada curso
        for (const [curso, usuarios] of Object.entries(reportesPorCurso)) {
            const cursoCard = document.createElement("div");
            cursoCard.className = "bg-white p-6 rounded-lg shadow-md";

            // Título del curso
            const cursoTitulo = document.createElement("h4");
            cursoTitulo.className = "text-gray-600 font-semibold text-xl mb-4";
            cursoTitulo.textContent = curso;
            cursoCard.appendChild(cursoTitulo);

            // Tabla para usuarios inscritos
            const tabla = document.createElement("table");
            tabla.className = "w-full table-auto border-collapse";
            tabla.innerHTML = `
                <thead>
                    <tr>
                        <th class="text-left border-b py-2">Nombre</th>
                        <th class="text-left border-b py-2">Email</th>
                        <th class="text-left border-b py-2">Fecha Compra</th>
                        <th class="text-left border-b py-2">Progreso</th>
                    </tr>
                </thead>
                <tbody>
                    ${usuarios
                    .map(
                        usuario => `
                        <tr>
                            <td class="py-2">${usuario.comprador_nombre}</td>
                            <td class="py-2">${usuario.comprador_email}</td>
                            <td class="py-2">${usuario.fecha_compra}</td>
                            <td class="py-2">${usuario.progreso}%</td>
                        </tr>
                    `
                    )
                    .join("")}
                </tbody>
            `;
            cursoCard.appendChild(tabla);

            // Agregar el card al contenedor
            reportesContainer.appendChild(cursoCard);
        }
    }

    // Función para obtener los cursos del instructor desde la API
    async function fetchCursos(instructorId) {
        try {
            const response = await fetch(`http://localhost/BDM-/Backend/API/APIReportes.php/reporte/Cursos?id=${instructorId}`);
            const cursos = await response.json();

            console.log(cursos); // Verifica que los cursos estén bien obtenidos

            // Llenar el carrusel con los cursos
            cargarCursosEnCarrusel(cursos);
        } catch (error) {
            console.error("Error al obtener los cursos:", error);
        }
    }

    // Función para cargar los cursos en el carrusel
    function cargarCursosEnCarrusel(cursos) {
        carouselContainer.innerHTML = ''; // Limpiar el carrusel

        cursos.forEach(curso => {
            const cursoItem = document.createElement('div');
            cursoItem.classList.add('carousel-item');
            cursoItem.innerHTML = `
                <div class="course space-y-5" onclick="openCourse('${curso.nombre_curso}')">
                    <img src="${curso.imagen_curso ? 'data:image/jpeg;base64,' + curso.imagen_curso : 'https://via.placeholder.com/400x200'}" alt="${curso.nombre_curso}">
                    <h3 class="font-semibold">${curso.nombre_curso}</h3>
                    <p>Estudiantes inscritos: ${curso.cantidad_inscritos}</p>
                    <p>Total de ganancias: $${curso.total_ganancias}</p>
                </div>
            `;
            carouselContainer.appendChild(cursoItem);
        });
    }

    // Llamar a las funciones de obtener cursos y reportes si existe el instructorId
    if (instructorId) {
        fetchReportesPorCurso(instructorId);
        fetchCursos(instructorId);
    } else {
        console.error("No se encontró el ID del instructor en el localStorage.");
    }
});
