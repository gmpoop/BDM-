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
    configurarCierreSesion(); // Configurar el botón de cierre de sesión
});
