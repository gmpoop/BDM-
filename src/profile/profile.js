// Obtener el token del localStorage
const jwtToken = localStorage.getItem('jwtToken');

// Función para obtener los datos del usuario
function getUserData() {
    const jwtToken = localStorage.getItem('jwtToken');

    console.log(jwtToken);

    fetch('http://localhost/BDM-/Backend/API/api.php/user/0', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${jwtToken}` 
            
        },
    })

        .then(response => {
            if (!response.ok) {
                return response.json().then(err => {
                    throw new Error(err.message || 'Error en la respuesta');
                });
            }
            return response.json();
        })
        .then(data => {
            console.log('Datos del usuario:', data);
            const user = data;
            if (user === null) {
                throw new Error('No se encontraron usuarios.');
            }
            console.log(user);
            document.getElementById('nombre-usuario').textContent = user.nombre_completo || '';
            document.getElementById('email-usuario').textContent = user.email || '';
            document.getElementById('birthdate').textContent = user.fecha_nacimiento || '';
            document.getElementById('gender').textContent = user.genero || 'Masculino';
            document.getElementById('fecha_registro').textContent = user.fecha_registro || '';

            
            document.getElementById('imagen-perfil').src = user.ruta_foto;

        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Hubo un error al obtener los datos del usuario.',
            });
        });
}
document.addEventListener('DOMContentLoaded', getUserData);

// Función para manejar el cierre de sesión
function configurarCierreSesion() {
    const cerrarSesionBtn = document.getElementById("cerrarSesionBtn");

    cerrarSesionBtn.addEventListener("click", () => {
        Swal.fire({
            title: '¿Estás seguro de que deseas cerrar sesión?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, cerrar sesión',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Eliminar datos de sesión
                localStorage.removeItem("authToken");
                sessionStorage.clear();

                // Mostrar mensaje de confirmación
                Swal.fire(
                    '¡Sesión cerrada!',
                    'Has cerrado sesión exitosamente.',
                    'success'
                ).then(() => {
                    // Redirigir al usuario a la página de inicio de sesión
                    window.location.href = "http://localhost/bdm-/src/login/Inicio_Sesion.html";
                });
            }
        });
    });
}

// Configurar eventos y cargar datos al iniciar
document.addEventListener('DOMContentLoaded', () => {
    getUserData(); // Cargar la información del usuario
    configurarCierreSesion();
    cargarCursos(); // Configurar el botón de cierre de sesión
});


async function cargarCursos() {
    try {
        // Obtener el token del localStorage
        const jwtToken = localStorage.getItem('jwtToken');
        
        if (!jwtToken) {
            console.error('No se encontró el token en el localStorage');
            return;
        }

        // Decodificar el token usando jwt-decode para obtener el ID del usuario
        const decodedToken = jwt_decode(jwtToken);
        const userId = decodedToken.data.id;

        if (!userId) {
            console.error('No se pudo obtener el ID del usuario del token');
            return;
        }

        // Llamada a la API para obtener los cursos del usuario usando su ID
        const response = await fetch(`http://localhost/BDM-/Backend/API/APIReportes.php/reporte/inscripciones?id=${userId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${jwtToken}` // Incluir el token en la cabecera
            }
        });

        const data = await response.json();

        if (response.ok) {
            // Limpiar la lista de cursos antes de agregar los nuevos
            const courseList = document.getElementById('course-list');
            courseList.innerHTML = ''; // Limpiar contenido

            // Iterar sobre los cursos obtenidos de la API
            data.forEach(course => {
                // Crear el contenedor para la card del curso
                const courseCard = document.createElement('div');
                courseCard.classList.add('bg-white', 'shadow-lg', 'rounded-lg', 'overflow-hidden');

                // Crear el contenido de la card
                const courseContent = `
                    <div class="p-6">
                        <h2 class="text-2xl font-bold text-[#4821ea]">${course.nombre_curso}</h2>
                        <p class="text-gray-600 mt-2">${course.descripcion_curso}</p>
                        <div class="mt-4">
                            <p class="text-sm text-gray-500">Progreso: <span class="font-semibold text-[#4821ea]">${course.progreso}%</span></p>
                            <p class="text-sm text-gray-500">Fecha de Inscripción: <span class="font-semibold text-[#4821ea]">${new Date(course.fecha_inscripcion).toLocaleDateString()}</span></p>
                        </div>
                        <div class="flex justify-center mt-6">
                            <button onclick="window.location.href='http://localhost/iCraft/src/templateCourse/courseVideo.html?idCurso=${course.id_curso}'"
                                    class="bg-[#4821ea] text-white py-2 px-6 rounded-lg hover:bg-[#3415b8]">
                                Ver Detalles del Curso
                            </button>
                        </div>
                    </div>
                `;

                // Añadir el contenido a la card
                courseCard.innerHTML = courseContent;

                // Agregar la card a la lista de cursos
                courseList.appendChild(courseCard);
            });
        } else {
            console.error('Error al obtener los cursos:', data.message);
        }
    } catch (error) {
        console.error('Error al hacer la solicitud a la API:', error);
    }
}
