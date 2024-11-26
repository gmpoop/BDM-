
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


async function cargarCursos() {
    try {

        const response = await fetch(PORT + "Cursos/APICursos.php/CursosCategorias", {
            headers: {
                'Authorization': `Bearer ${jwtToken}`,
            },
            method: 'GET'
        });
        const cursos = await response.json();

        const contenedor = document.getElementById('categorias-contenedor');

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
            categoriaDiv.classList.add('w-full', 'h-auto', 'bg-white', 'rounded-xl', 'flex-col', 'm-5', 'overflow-x-auto');
            
            const tituloCategoria = document.createElement('h1');
            tituloCategoria.classList.add('text-5xl', 'font-bold', 'ml-4', 'mb-4', 'text-[#000000]');
            tituloCategoria.textContent = nombreCategoria;

            const listaCursos = document.createElement('ul');
            listaCursos.classList.add('flex', 'overflow-x-auto');

            cursos.forEach(curso => {
                const itemCurso = document.createElement('li');
                itemCurso.classList.add('shadow-inner', 'min-w-[500px]', 'flex-col', 'space-y-4', 'justify-between', 'items-center', 'm-2', 'p-4', 'hover:opacity-90');
                
                const enlaceCurso = document.createElement('a');
                enlaceCurso.href = `../templateCourse/course.html?curso_id=${curso.id_curso}`;

                const tituloCurso = document.createElement('h2');
                tituloCurso.classList.add('text-2xl', 'font-bold', 'text-gray-600');
                tituloCurso.textContent = curso.titulo;

                const imagenCurso = document.createElement('img');
                imagenCurso.src = curso.imagen;
                imagenCurso.alt = curso.titulo;
                imagenCurso.classList.add('rounded-lg', 'mb-4');

                const descripcionCurso = document.createElement('p');
                descripcionCurso.classList.add('text-gray-400');
                descripcionCurso.textContent = curso.descripcion;

                const botonCarrito = document.createElement('button');
                botonCarrito.classList.add('bg-[#4821ea]', 'text-white', 'py-2', 'px-4', 'rounded', 'hover:bg-[#3d1bc8]');
                botonCarrito.textContent = 'Agregar al carrito';
                botonCarrito.onclick = () => agregarAlCarrito(curso.id_curso);

                enlaceCurso.appendChild(tituloCurso);
                enlaceCurso.appendChild(imagenCurso);

                itemCurso.appendChild(enlaceCurso);
                itemCurso.appendChild(descripcionCurso);
                itemCurso.appendChild(botonCarrito);

                listaCursos.appendChild(itemCurso);
            });

            categoriaDiv.appendChild(tituloCategoria);
            categoriaDiv.appendChild(listaCursos);
            contenedor.appendChild(categoriaDiv);
        }
    } catch (error) {
        console.error('Error al cargar los datos:', error);
    }
}


document.addEventListener('DOMContentLoaded', () => {
    verifyToken();
    cargarCursos();
});

