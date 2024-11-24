document.getElementById('guardarCateBtn').addEventListener('click', function (event) {
    console.log("Botón clickeado");
    event.preventDefault(); // Evita que el formulario se envíe de forma tradicional

    // Recoger datos del formulario
    const nombre = document.getElementById('nombreCate').value.trim();
    const descripcion = document.getElementById('descripcionCate').value.trim();
    const estado = document.getElementById('estado').value; // Puedes usar esto si necesitas el estado

    // Validación básica de campos
    if (!nombre || !descripcion) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos',
            text: 'Por favor completa todos los campos antes de guardar.',
        });
        return;
    }

    // Obtener el token de localStorage
    const jwtToken = localStorage.getItem('jwtToken');

    if (!jwtToken) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se encontró el token de autenticación.',
        });
        return;
    }

    // Decodificar el JWT para obtener el usuario ID
    const decodedToken = JSON.parse(atob(jwtToken.split('.')[1]));  // Decodifica el JWT manualmente
    const usuarioCreadorId = decodedToken.data.id;  // Accedemos al ID dentro de la clave 'data'

    if (!usuarioCreadorId) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'No se pudo obtener el ID del usuario.',
        });
        return;
    }

    // Crear el objeto de datos a enviar
    const categoriaData = {
        nombre: nombre,
        descripcion: descripcion,
        usuario_creador_id: usuarioCreadorId // Usar el ID del usuario extraído del token
    };

    // Enviar datos a la API
    fetch('http://localhost/BDM/BDM/Backend/API/APIcategorias.php/categorias', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(categoriaData)
    })
    .then(response => {
        if (!response.ok) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error en la creación de la categoría.',
            });
            throw new Error('Error en la creación de la categoría');
        }
        return response.json();
    })
    .then(data => {
        console.log(data); // Maneja la respuesta del servidor

        // Muestra un mensaje de éxito
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: 'Categoría creada con éxito.',
        });

        // Limpia los campos del formulario
        document.getElementById('nombreCate').value = '';
        document.getElementById('descripcionCate').value = '';
        document.getElementById('estado').value = 'activo';
    })
    .catch(error => {
        console.error('Error:', error);
        // Mensaje de error adicional
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'Ocurrió un problema al conectar con el servidor.',
        });
    });
});

// Obtener todas las categorías para llenar el select
function cargarCategoriasUpdate() {
    fetch('http://localhost/BDM/BDM/Backend/API/APIcategorias.php/categorias', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        const select = document.getElementById('categoriaSelect');
        select.innerHTML = '<option value="" disabled selected>Selecciona una categoría</option>'; // Limpiar opciones previas
        data.forEach(categoria => {
            const option = document.createElement('option');
            option.value = categoria.id;
            option.textContent = categoria.nombre;
            select.appendChild(option);
        });
    })
    .catch(error => {
        console.error('Error cargando categorías:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al cargar las categorías.',
        });
    });
}

// Habilitar campos de edición cuando se selecciona una categoría
document.getElementById('categoriaSelect', ).addEventListener('change', function() {
    const categoriaId = this.value;
    if (categoriaId) {
        // Habilitar campos de texto y el botón de guardar
        document.getElementById('nombreCategoria').disabled = false;
        document.getElementById('descripcionCategoria').disabled = false;
        document.getElementById('estadoCategoria').disabled = false;
        document.getElementById('guardarCambiosBtn').disabled = false;

        // Obtener los detalles de la categoría seleccionada
        fetch(`http://localhost/BDM/BDM/Backend/API/APIcategorias.php/categoria/${categoriaId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            // Rellenar los campos con los datos de la categoría
            document.getElementById('nombreCategoria').value = data.nombre;
            document.getElementById('descripcionCategoria').value = data.descripcion;
            document.getElementById('estadoCategoria').value = data.estado;
        })
        .catch(error => {
            console.error('Error obteniendo los detalles de la categoría:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Ocurrió un error al obtener los detalles de la categoría.',
            });
        });
    } else {
        // Deshabilitar los campos si no hay categoría seleccionada
        document.getElementById('nombreCategoria').disabled = true;
        document.getElementById('descripcionCategoria').disabled = true;
        document.getElementById('estadoCategoria').disabled = true;
        document.getElementById('guardarCambiosBtn').disabled = true;
    }
});

// Guardar los cambios de la categoría
document.getElementById('guardarCambiosBtn').addEventListener('click', function(event) {
    event.preventDefault();

    const categoriaId = document.getElementById('categoriaSelect').value;
    const nombre = document.getElementById('nombreCategoria').value.trim();
    const descripcion = document.getElementById('descripcionCategoria').value.trim();
    const estado = document.getElementById('estadoCategoria').value;

    if (!nombre || !descripcion) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos',
            text: 'Por favor completa todos los campos antes de guardar.',
        });
        return;
    }

    const categoriaData = {
        nombre: nombre,
        descripcion: descripcion,
        estado: estado
    };

    fetch(`http://localhost/BDM/BDM/Backend/API/APIcategorias.php/categoria/${categoriaId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(categoriaData)
    })
    .then(response => {
        if (!response.ok) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al guardar los cambios.',
            });
            throw new Error('Error al guardar los cambios');
        }
        return response.json();
    })
    .then(data => {
        Swal.fire({
            icon: 'success',
            title: 'Categoría Actualizada',
            text: 'La categoría ha sido actualizada con éxito.',
        });
        
        // Limpiar los campos de edición
        document.getElementById('nombreCategoria').value = '';
        document.getElementById('descripcionCategoria').value = '';
        document.getElementById('estadoCategoria').value = 'activo';
        
        // Deshabilitar los campos y el botón nuevamente
        document.getElementById('nombreCategoria').disabled = true;
        document.getElementById('descripcionCategoria').disabled = true;
        document.getElementById('estadoCategoria').disabled = true;
        document.getElementById('guardarCambiosBtn').disabled = true;

        cargarCategoriasUpdate(); // Recargar las categorías
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'Ocurrió un problema al conectar con el servidor.',
        });
    });
});

// Cargar las categorías cuando se cargue la página
document.addEventListener('DOMContentLoaded', function() {
    cargarCategoriasUpdate();
});

// Obtener todas las categorías para llenar el select
function cargarCategorias() {
    fetch('http://localhost/BDM/BDM/Backend/API/APIcategorias.php/categorias', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        const select = document.getElementById('categoriaDeleteSelect');
        select.innerHTML = '<option value="" disabled selected>Selecciona una categoría</option>'; // Limpiar opciones previas
        data.forEach(categoria => {
            const option = document.createElement('option');
            option.value = categoria.id;
            option.textContent = categoria.nombre;
            select.appendChild(option);
        });
    })
    .catch(error => {
        console.error('Error cargando categorías:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al cargar las categorías.',
        });
    });
}
// Manejar la eliminación de una categoría
document.getElementById('eliminarBtn').addEventListener('click', function(event) {
    event.preventDefault();

    const categoriaId = document.getElementById('categoriaDeleteSelect').value;

    if (!categoriaId) {
        Swal.fire({
            icon: 'warning',
            title: 'Selecciona una categoría',
            text: 'Por favor selecciona una categoría para eliminar.',
        });
        return;
    }

    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`http://localhost/BDM/BDM/Backend/API/APIcategorias.php/categoria/${categoriaId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un problema al eliminar la categoría.',
                    });
                    throw new Error('Error al eliminar la categoría');
                }
                return response.json();
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: 'Categoría eliminada',
                    text: 'La categoría ha sido eliminada con éxito.',
                });
                cargarCategorias(); // Recargar las categorías
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error de conexión',
                    text: 'Ocurrió un problema al conectar con el servidor.',
                });
            });
        }
    });
});

// Activar o desactivar el botón de eliminar cuando se selecciona una categoría
document.getElementById('categoriaDeleteSelect').addEventListener('change', function() {
    const categoriaId = this.value;
    const eliminarBtn = document.getElementById('eliminarBtn');
    
    // Habilitar el botón de eliminar solo cuando se selecciona una categoría
    if (categoriaId) {
        eliminarBtn.disabled = false;
    } else {
        eliminarBtn.disabled = true;
    }
});

// Llamar a cargarCategorias para llenar el select al cargar la página
document.addEventListener('DOMContentLoaded', cargarCategorias);
