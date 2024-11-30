
const jwtToken = localStorage.getItem('jwtToken');

const PORT = "http://localhost/BDM-/Backend/API/";


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

const GetCategories = async () => {

    const CreateCategorieResponse = await fetch(PORT + "APIcategorias.php/categorias", {
        headers: {
            'Authorization': `Bearer ${jwtToken}`,
        },
        method: 'GET'
    });

        const data = await CreateCategorieResponse.json();
        console.log("Categorias data", data);

    if (data) {
        return data;
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al conseguir las categorias.',
        });
    }
}

async function cargarCursos() {
    try {

        const response = await fetch(PORT + "Cursos/APICursos.php/CursosCategorias", {
            headers: {
                'Authorization': `Bearer ${jwtToken}`,
            },
            method: 'GET'
        });
        const cursos = await response.json();
        ProcesarCursos(cursos);

        
    } catch (error) {
        console.error('Error al cargar los datos:', error);
    }
}

async function BuscarCursos() {
    // Capturar valores de búsqueda y filtros
    const searchQuery = document.getElementById('searchInput').value;
    const startDate = document.getElementById('startDate').value;
    const endDate = document.getElementById('endDate').value;
    const category = document.getElementById('categoryFilter').value;
    const status = document.getElementById('statusFilter').value;

    // Construir la URL de la API con parámetros
    let url = `/BDM-/Backend/API/APIbusqueda.php/cursos/buscar?`;

    if (searchQuery) url += `titulo=${encodeURIComponent(searchQuery)}&`;
    if (startDate) url += `start_date=${encodeURIComponent(startDate)}&`;
    if (endDate) url += `end_date=${encodeURIComponent(endDate)}&`;
    if (category) url += `categoria_id=${encodeURIComponent(category)}&`;
    if (status) {
        console.log(status)
        url += `estado=${encodeURIComponent(status)}&`;
        
    }

    // Remover el último ampersand (&) si está presente
    url = url.slice(-1) === '&' ? url.slice(0, -1) : url;

    try {
        // Realizar la solicitud a la API
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                
            },
        });

        // Obtener la respuesta en JSON
        const data = await response.json();
        ProcesarCursos(data);

        // Mostrar resultados en la página
    } catch (error) {
        console.error('Error al buscar cursos:', error);
    }
}

function ProcesarCursos(cursos){

    const contenedor = document.getElementById('categorias-contenedor');
    contenedor.innerHTML = '';  
        // Crear un objeto para agrupar cursos por categoría
        const categorias = {};

        console.log(cursos);

        cursos.records.forEach(curso => {
            if (!categorias[curso.nombre_categoria]) {
                categorias[curso.nombre_categoria] = [];
            }
            categorias[curso.nombre_categoria].push(curso);
        });

        console.log(categorias);

        // Generar el HTML para cada categoría y sus cursos
        for (const [nombreCategoria, cursos] of Object.entries(categorias)) {
            console.log(nombreCategoria, cursos);

            const categoriaDiv = document.createElement('div');
            categoriaDiv.classList.add('w-full', 'h-auto', 'bg-white', 'rounded-xl', 'flex-col', 'overflow-x-auto');

            const tituloCategoria = document.createElement('h1');
            tituloCategoria.classList.add('text-3xl', 'font-bold', 'ml-4', 'mb-4', 'text-[#000000]');
            tituloCategoria.textContent = nombreCategoria;

            const listaCursos = document.createElement('ul');
            listaCursos.classList.add('flex', 'overflow-x-auto', 'max-h-[500px]');

            cursos.forEach(curso => {
                const itemCurso = document.createElement('li');
                itemCurso.classList.add('shadow-inner', 'min-w-[500px]', 'max-w-[600px]' , 'flex-col', 'space-y-4', 'justify-between', 'items-center', 'm-2', 'p-4', 'hover:opacity-90');
                
                const enlaceCurso = document.createElement('a');
                enlaceCurso.href = `../templateCourse/course.html?curso_id=${curso.id_curso}`;

                const tituloCurso = document.createElement('h2');
                tituloCurso.classList.add('text-2xl', 'text-gray-600');
                tituloCurso.textContent = curso.titulo;


                const imagenCurso = document.createElement('img');
                imagenCurso.src = curso.imagen;
                imagenCurso.alt = curso.titulo;
                imagenCurso.classList.add('rounded-lg', 'object-scale-down'
                  , 'h-[200px]'  , 'max-h-[200px]', 'p-5'
                );

                const contenedorImagen = document.createElement('div');
                contenedorImagen.classList.add('flex', 'justify-center')
                contenedorImagen.appendChild(imagenCurso);

                const descripcionCurso = document.createElement('p');
                descripcionCurso.classList.add('text-gray-400', 'h-[100px]','max-h-[100px]', 'overflow-hidden');
                descripcionCurso.textContent = curso.descripcion;

                const botonCarrito = document.createElement('button');
                botonCarrito.classList.add('bg-[#4821ea]', 'text-white', 'py-2', 'px-4', 'rounded', 'hover:bg-[#3d1bc8]');
                botonCarrito.textContent = 'Agregar al carrito';
                
                // Verificar si el curso ya está en el carrito
                const carrito = cargarCarrito();
                if (carrito.some(item => item.id === curso.id_curso)) {
                    botonCarrito.textContent = 'Agregado';
                    botonCarrito.disabled = true;
                }
                
                botonCarrito.onclick = () => {
                    AgregarAlCarrito(curso);
                    botonCarrito.textContent = 'Agregado';
                    botonCarrito.disabled = true;
                    actualizarContadorCarrito(); // Actualiza el contador del carrito
                };
                

                enlaceCurso.appendChild(tituloCurso);
                enlaceCurso.appendChild(contenedorImagen);

                itemCurso.appendChild(enlaceCurso);
                itemCurso.appendChild(descripcionCurso);
                itemCurso.appendChild(botonCarrito);

                listaCursos.appendChild(itemCurso);
            });

            categoriaDiv.appendChild(tituloCategoria);
            categoriaDiv.appendChild(listaCursos);
            contenedor.appendChild(categoriaDiv);
        }

}

document.addEventListener('DOMContentLoaded', () => {

    const InitComboCategorias = async () => {
        const categoriaSelect = document.getElementById('categoryFilter');
    
        const Categories =  await GetCategories();
        
        console.log("Categorias", Categories);  
    
        // Crear las nuevas opciones
        Categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.nombre;
            categoriaSelect.appendChild(option);
        });
    }  

    actualizarContadorCarrito();
    verifyToken();
    InitComboCategorias();
    cargarCursos();

 
});

document.getElementById("searchInput").addEventListener("change", () => {
    console.log("Cambiando");
    BuscarCursos();
});

function cargarCarrito() {
    return JSON.parse(localStorage.getItem('carrito')) || [];
}
function guardarCarrito(carrito) {
    localStorage.setItem('carrito', JSON.stringify(carrito));
}
function actualizarContadorCarrito() {
    const carrito = cargarCarrito();
    const contador = document.querySelector('.carrito-contador'); // Asegúrate de usar esta clase en el span del ícono
    contador.textContent = carrito.length;
}
function AgregarAlCarrito(curso) {
    const carrito = cargarCarrito();

    // Si el curso no está en el carrito, agregarlo
    if (!carrito.some(item => item.id === curso.id_curso)) {
        carrito.push({
            id: curso.id_curso,
            titulo: curso.titulo,
            precio: curso.precio,
            imagen: curso.imagen,
            descripcion: curso.descripcion
        });
        guardarCarrito(carrito);

        // SweetAlert para confirmar que se agregó el curso
        Swal.fire({
            title: '¡Agregado al carrito!',
            text: `${curso.titulo} ha sido agregado.`,
            icon: 'success',
            confirmButtonText: 'Aceptar',
            timer: 1000, // Mensaje desaparece automáticamente después de 2 segundos
            timerProgressBar: true
        });
    } else {
        // SweetAlert para indicar que el curso ya estaba en el carrito
        Swal.fire({
            title: 'Curso ya en el carrito',
            text: 'Este curso ya está en tu carrito.',
            icon: 'info',
            confirmButtonText: 'Aceptar'
        });
    }
}
