const jwtToken = localStorage.getItem('jwtToken');
const BACKEND_BASE_URL = "http://localhost/BDM-/Backend/"; // Base URL del backend

// Función para obtener los datos del usuario
function getUserData() {
    const jwtToken = localStorage.getItem('jwtToken');
    
    if (!jwtToken) {
        console.error('No se encontró el token JWT en localStorage.');
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No estás autenticado. Por favor, inicia sesión nuevamente.',
        });
        return;
    }

    fetch('http://localhost/BDM-/Backend/API/api.php/user/0', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${jwtToken}`,
            'Content-Type': 'application/json',
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
    .then(user => {
        if (!user || user.id === undefined) {
            throw new Error('No se encontró información del usuario.');
        }

        // Construir la ruta completa de la imagen
        const rutaImagenCompleta = `${BACKEND_BASE_URL}${user.ruta_foto.replace("../", "")}`;
        // Actualizar los elementos de la interfaz con la información del usuario
        document.getElementById('nombre-usuario').textContent = user.nombre_completo || 'Usuario desconocido';
        document.getElementById('userEmail').textContent = user.email || 'Sin correo';
        document.getElementById('avatar').src = rutaImagenCompleta || '../resources/images/UserAvatar.jpg'; // Ruta por defecto
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
configurarCierreSesion(); // Configurar el botón de cierre de sesión
});
