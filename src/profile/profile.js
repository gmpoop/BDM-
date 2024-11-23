// Obtener el token del localStorage
    const jwtToken = localStorage.getItem('jwtToken');

    // Función para obtener los datos del usuario
    function getUserData() {
        const jwtToken = localStorage.getItem('jwtToken');
        
        fetch('http://localhost/BDM-/Backend/API/api.php/users', {
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
        .then(data => {
            const user = data.data;
            if (!user) {
                throw new Error('No se encontraron usuarios.');
            }
            document.getElementById('nombre').value = user.nombre_completo || '';
            document.getElementById('email').value = user.email || '';
            document.getElementById('birthdate').value = user.fecha_nacimiento || '';
            document.getElementById('gender').value = user.genero || 'Masculino';
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
    actualizarInformacionUsuario(); // Cargar la información del usuario
    configurarCierreSesion(); // Configurar el botón de cierre de sesión
});
