const token = localStorage.getItem("jwtToken");

let usuarioId = null; // Definir usuarioId aquí para que sea accesible globalmente

if (token && token.split('.').length === 3) {
    try {
        const decoded = jwt_decode(token);
        usuarioId = decoded.data.id;  // usuario_id
    } catch (error) {
        console.error("Error al decodificar el token:", error.message);
    }
} else {
    console.error("Token inválido o no encontrado.");
}

// Variables para los filtros
let fechaInicio = null;
let fechaFin = null;
let categoria = null;
let estadoCurso = null;

// Llamada inicial a la API sin filtros
if (token && token.split('.').length === 3) {
    obtenerKardex(usuarioId); // Llamar a la función con el usuarioId
}


// Mostrar y ocultar el menú de filtros
document.getElementById('filterButton').addEventListener('click', function () {
    var filterMenu = document.getElementById('filterMenu');
    filterMenu.classList.toggle('hidden');
});

function toggleMenu(element) {
    var menu = element.nextElementSibling;
    menu.classList.toggle('hidden');
}

document.addEventListener('click', function (event) {
    var isClickInside = document.getElementById('filterButton').contains(event.target) || document.getElementById('filterMenu').contains(event.target);
    if (!isClickInside) {
        document.getElementById('filterMenu').classList.add('hidden');
    }

    var ellipsisMenus = document.querySelectorAll('.fa-ellipsis-v');
    ellipsisMenus.forEach(function (ellipsis) {
        var menu = ellipsis.nextElementSibling;
        if (!ellipsis.contains(event.target) && !menu.contains(event.target)) {
            menu.classList.add('hidden');
        }
    });
});

// Aplicar filtros
document.getElementById('applyFilters').addEventListener('click', () => {
    fechaInicio = document.getElementById('fechaInicio').value;
    fechaFin = document.getElementById('fechaFin').value;
    categoria = document.getElementById('categoria').value !== 'Todas' ? document.getElementById('categoria').value : null;
    estadoCurso = document.getElementById('estadoCurso').value !== 'Todos' ? document.getElementById('estadoCurso').value : null;

    obtenerKardex(usuarioId); // Llamar a la función con el usuarioId
});

async function obtenerKardex(usuarioId) {
    const url = new URL('http://localhost/BDM/iCraft/Backend/API/APIKardex.php/kardex/estudiante');
    const params = new URLSearchParams();

    params.append('usuario_id', usuarioId);

    if (fechaInicio && fechaFin) {
        params.append('fecha_inicio', fechaInicio);
        params.append('fecha_fin', fechaFin);
    }
    if (categoria && categoria !== 'Todas') {
        params.append('categoria', categoria);
    }
    if (estadoCurso && estadoCurso !== 'Todos') {
        params.append('estatus', estadoCurso);
    }

    try {
        const response = await fetch(`${url}?${params.toString()}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();

        // Mostrar los datos en la tabla
        if (data.length > 0) {
            const tbody = document.querySelector('tbody');
            tbody.innerHTML = '';
            const categorias = new Set(); // Usar un Set para obtener categorías únicas

            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.classList.add('border-b', 'border-gray-200', 'hover:bg-gray-100');
                tr.innerHTML = `
                    <td class="py-4 px-6 text-left">${item.curso}</td>
                    <td class="py-4 px-6 text-left">${item.fecha_inscripcion}</td>
                    <td class="py-4 px-6 text-left">${item.progreso}%</td>
                    <td class="py-4 px-6 text-left">
                        <span class="bg-${item.estatus === 'Completado' ? 'green' : 'orange'}-100 text-${item.estatus === 'Completado' ? 'green' : 'orange'}-600 py-1 px-3 rounded-full text-xs">
                            ${item.estatus}
                        </span>
                    </td>
                    <td class="py-4 px-6 text-left">${item.fecha_terminacion || '-'}</td>
                    <td class="py-4 px-6 text-left relative">
                        <i class="fas fa-ellipsis-v cursor-pointer" onclick="toggleMenu(this)"></i>
                        <div class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-md shadow-lg hidden">
                            <a href="javascript:void(0);" class="block px-4 py-2 text-gray-800 hover:bg-gray-100" onclick="validarCertificado('${item.estatus}', ${item.curso_id})">Obtener certificado</a>
                        </div>
                    </td>
            `;
                tbody.appendChild(tr);

                // Agregar la categoría al conjunto
                categorias.add(item.categoria);
            });


            // Llenar el select de categorías
            const categoriaSelect = document.getElementById('categoria');
            categoriaSelect.innerHTML = '<option value="Todas">Todas</option>'; // Reiniciar opciones

            categorias.forEach(categoria => {
                const option = document.createElement('option');
                option.value = categoria; // O puedes usar el ID si es necesario
                option.textContent = categoria;
                categoriaSelect.appendChild(option);
            });
        } else {
            // Mostrar mensaje de "No se encontraron registros"
            Swal.fire({
                icon: 'info',
                title: 'No se encontraron registros',
                text: 'No hay cursos que coincidan con los filtros seleccionados.',
                confirmButtonText: 'Cerrar'
            });
        }
    } catch (error) {
        console.error('Error al obtener el Kardex:', error);
        // Mostrar mensaje de error
        Swal.fire({
            icon: 'error',
            title: 'Hubo un error',
            text: 'No se pudieron obtener los datos del Kardex. Intenta nuevamente más tarde.',
            confirmButtonText: 'Cerrar'
        });
    }
}

function validarCertificado(estatusCurso, cursoId) {
    if (estatusCurso !== 'Completado') {
        // Mostrar una alerta si el curso no está completado
        Swal.fire({
            icon: 'warning',
            title: 'Curso no completado',
            text: 'No puedes obtener el certificado hasta que el curso esté completado.',
            confirmButtonText: 'Cerrar'
        });
    } else {
        // Guardar el curso_id en localStorage
        localStorage.setItem('curso_id', cursoId);

        // Si el curso está completado, redirigir al certificado
        window.location.href = "../Kardex/Certificado.html";
    }
}
