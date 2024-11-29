
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
});


