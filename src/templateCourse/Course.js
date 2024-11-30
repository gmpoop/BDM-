
// Obtener la URL actual
url = new URL(window.location.href);

console.log(url); // Salida: http://localhost:3000/profile.html?id=123
// Obtener el valor del parámetro 'id'
const idUrl = url.searchParams.get('curso_id');

// Obtén el token JWT del local storage
const jwtToken = localStorage.getItem('jwtToken');

const PORT = "http://localhost/iCraft/Backend/API/";

const verifyToken = async () => {
    try {
        const response = await fetch(PORT + "api.php/verfiyToken", {
            headers: {
                'Authorization': `Bearer ${jwtToken}`,
            },
            method: 'GET'
        });

        response.json().then(data => {
            console.log("Token verificado", data);
            if (data.message === "Token resuelto") {
                decodedToken = data.data.data.id;
                console.log("ID del usuario", decodedToken);
            }
        }).catch(error => {

        });
    }
    catch {

    }
};

async function getCourseData() {
    try {
        console.log("Verificando curso...");

        const response = await fetch(PORT + "Cursos/APICursos.php/CursosDetalle?curso_id=" + idUrl, {
            headers: {
                'Authorization': `Bearer ${jwtToken}`,
            },
            method: 'GET'
        });

        const data = await response.json();

        if (data.records && data.records.length > 0) {
            // Tomar el primer registro de curso
            const curso = data.records[0];

            // Actualizar imagen del curso
            document.getElementById('curso-imagen').src = curso.imagen;
            document.getElementById('curso-imagen').alt = curso.titulo_curso;

            // Actualizar título del curso
            document.getElementById('curso-titulo').textContent = curso.titulo_curso;
            document.getElementById('curso-detalle-titulo').textContent = curso.titulo_curso;

            // Actualizar descripción del curso
            document.getElementById('curso-descripcion').textContent = curso.descripcion;

            // Actualizar información del instructor
            document.getElementById('curso-instructor').textContent = "Creado por: " + curso.nombre_completo;

            // Limpiar lista de niveles antes de añadir nuevos
            const nivelesLista = document.getElementById('niveles-lista');
            nivelesLista.innerHTML = '';

            // Añadir los niveles
            data.records.forEach(nivel => {
                const itemNivel = document.createElement('li');

                itemNivel.classList.add('h-auto', 'min-h-[300px]', 'min-w-[250px]', 'flex-col', 'items-start', 'items-center', 'p-4', 'relattive');
                
                const nivelDiv = document.createElement('div');
                nivelDiv.classList.add('flex', 'flex-col', 'items-start');
                
                const nivelImg = document.createElement('img');
                nivelImg.src = "https://via.placeholder.com/300x200";  // Puedes actualizar esto para la imagen real si está disponible
                nivelImg.alt = `Nivel `;
                nivelImg.classList.add('rounded-lg', 'mb-4', 'object-scale-down');

                const nivelTitulo = document.createElement('p');
                nivelTitulo.classList.add('text-gray-500', 'max-x-full', 'overlow-hidden', 'text-sm');
                nivelTitulo.textContent = "Titulo del nivel: " + nivel.titulo_nivel;

                nivelDiv.appendChild(nivelImg);
                nivelDiv.appendChild(nivelTitulo);
                itemNivel.appendChild(nivelDiv);
                nivelesLista.appendChild(itemNivel);
            });
        } else {
            console.error('No se encontraron detalles para el curso.');
        }

    } catch (error) {
        console.error('Error al cargar los datos:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    verifyToken();
    getCourseData();
    obtenerComentarios(idUrl);
});

// Función para obtener los comentarios de la API
async function obtenerComentarios(curso_id) {
    try {
        const response = await fetch(`http://localhost/BDM-/Backend/API/APIcomentarios.php/comentarios/curso/${curso_id}`);
        const comentarios = await response.json();

        // Llamar a la función para mostrar los comentarios
        mostrarComentarios(comentarios);
    } catch (error) {
        console.error('Error al obtener los comentarios:', error);
    }
}
// Función para mostrar los comentarios dinámicamente
function mostrarComentarios(comentarios) {
    const commentsContainer = document.getElementById('comments-container');
    commentsContainer.innerHTML = ''; // Limpiar comentarios previos

    comentarios.forEach((comentario, index) => {
        // Crear el contenedor del comentario
        const comentarioDiv = document.createElement('div');
        comentarioDiv.classList.add('bg-white', 'p-4', 'rounded-lg', 'mb-4', 'shadow-md', 'bg-opacity-70'); // Fondo transparente

        // Usuario y nombre
        const usuarioDiv = document.createElement('div');
        usuarioDiv.classList.add('flex', 'items-center', 'mb-4');

        const userInitials = document.createElement('div');
        userInitials.classList.add('bg-[#4821ea]', 'w-10', 'h-10', 'rounded-full', 'flex', 'items-center', 'justify-center', 'text-white', 'font-bold', 'mr-3');
        userInitials.textContent = comentario.usuario_nombre ? comentario.usuario_nombre.charAt(0).toUpperCase() : 'N/A';

        const userName = document.createElement('h3');
        userName.classList.add('text-lg', 'font-semibold', 'text-gray-700');
        userName.textContent = comentario.usuario_nombre || 'Usuario desconocido';

        usuarioDiv.appendChild(userInitials);
        usuarioDiv.appendChild(userName);

        // Calificación
        const calificacionContainer = document.createElement('div');
        calificacionContainer.classList.add('flex', 'items-center', 'gap-1', 'mb-4');

        const calificacionTitulo = document.createElement('h2');
        calificacionTitulo.classList.add('text-lg', 'font-semibold', 'text-gray-700');
        calificacionTitulo.textContent = 'Calificación';

        const starsDiv = document.createElement('div');
        starsDiv.classList.add('flex', 'gap-1');

        const calificacion = parseInt(comentario.calificacion) || 0; // Asegurar un valor numérico

        // Generar estrellas dinámicamente
        for (let i = 1; i <= 5; i++) {
            const starSvg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            starSvg.classList.add('w-6', 'h-6');
            starSvg.setAttribute('fill', 'currentColor');
            starSvg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
            starSvg.setAttribute('viewBox', '0 0 20 20');

            // Crear el elemento path dentro del SVG
            const starPath = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            starPath.setAttribute('d', 'M10 15.27L16.18 19l-1.64-7.03L18 7.24l-7.19-.61L10 0 9.19 6.63 2 7.24l4.46 4.73L3.82 19z');

            // Aquí controlamos el color de la estrella
            if (i <= calificacion) {
                starPath.setAttribute('fill', '#f59e0b');  // Estrella llena
            } else {
                starPath.setAttribute('fill', 'gray');  // Estrella vacía
            }

            // Añadir el path al SVG
            starSvg.appendChild(starPath);

            // Añadir el SVG al contenedor de estrellas
            starsDiv.appendChild(starSvg);
        }

        calificacionContainer.appendChild(calificacionTitulo);
        calificacionContainer.appendChild(starsDiv);

        // Comentario
        const comentarioTexto = document.createElement('p');
        comentarioTexto.classList.add('text-gray-600', 'mb-4');
        comentarioTexto.textContent = comentario.comentario;

        // Fecha
        const fechaDiv = document.createElement('div');
        fechaDiv.classList.add('text-sm', 'text-gray-500');
        fechaDiv.textContent = calcularTiempo(comentario.fecha_creacion);

        // Ensamblar el comentario
        comentarioDiv.appendChild(usuarioDiv);
        comentarioDiv.appendChild(calificacionContainer);
        comentarioDiv.appendChild(comentarioTexto);
        comentarioDiv.appendChild(fechaDiv);

        // Agregar al contenedor principal
        commentsContainer.appendChild(comentarioDiv);
    });
}
// Función para calcular el tiempo
function calcularTiempo(fecha) {
    const now = new Date();
    const createdAt = new Date(fecha);
    const diff = now - createdAt;
    const hours = Math.floor(diff / (1000 * 60 * 60));
    const minutes = Math.floor(diff / (1000 * 60));
    if (minutes < 60) return `Hace ${minutes} minutos`;
    if (hours < 24) return `Hace ${hours} horas`;
    const days = Math.floor(hours / 24);
    return `Hace ${days} días`;
}

// Obtener los elementos de las estrellas
const stars = document.querySelectorAll('#star-rating svg');
let rating = 0;

// Funcionalidad de estrellas para calificación
stars.forEach((star, index) => {
    star.addEventListener('click', () => {
        rating = index + 1; // Establecer calificación
        stars.forEach((s, i) => {
            if (i <= index) {
                s.classList.add('text-yellow-500');
            } else {
                s.classList.remove('text-yellow-500');
            }
        });
    });
});

// Obtener el token del localStorage
const token = localStorage.getItem('jwtToken'); // Suponiendo que el token se guarda bajo la clave 'token'

// Función para decodificar el token y obtener el usuario_id
function obtenerUsuarioIdDesdeToken(token) {
    if (!token) return null;

    // El JWT está compuesto por tres partes separadas por "."
    const payloadBase64 = token.split('.')[1]; // Obtener la parte del payload (el cuerpo del JWT)

    // Decodificar de Base64 a texto
    const payloadJson = atob(payloadBase64);

    // Convertir el payload a un objeto JSON
    const payload = JSON.parse(payloadJson);

    // Retornar el usuario_id del payload
    return payload.data.id;
}

// Obtener el usuario_id desde el token
const usuario_id = obtenerUsuarioIdDesdeToken(token);

// Enviar comentario a la API
document.getElementById('btn-add-comment').addEventListener('click', async () => {
    const comentario = document.getElementById('comentario').value;

    if (!comentario || rating === 0) {
        Swal.fire({
            icon: 'warning',
            title: '¡Espera!',
            text: 'Por favor, agrega un comentario y una calificación.',
        });
        return;
    }

    const data = {
        curso_id: idUrl,
        usuario_id: usuario_id,
        comentario: comentario,
        calificacion: rating
    };

    try {
        const response = await fetch('http://localhost/BDM-/Backend/API/APIcomentarios.php/comentarios', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            // Mostrar alerta de éxito
            Swal.fire({
                icon: 'success',
                title: 'Comentario añadido',
                text: 'Tu comentario fue añadido exitosamente.',
            });

            // Limpiar campos
            document.getElementById('comentario').value = '';
            stars.forEach(star => star.classList.remove('text-yellow-500'));
            rating = 0; // Resetear rating
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.message || 'Hubo un problema al añadir el comentario.',
            });
        }

        obtenerComentarios(idUrl);
    } catch (error) {
        console.error("Error:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'Hubo un problema con la conexión al servidor.',
        });
    }
});