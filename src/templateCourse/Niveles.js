// Obtener la URL actual
var url = new URL(window.location.href);

console.log(url); // Salida: http://localhost:3000/profile.html?id=123
// Obtener el valor del parámetro 'id'
const idCurso = url.searchParams.get('idCurso');

const getNiveles = async (idCurso) => {

    console.log('ID:', idCurso);

    const jwtToken = localStorage.getItem('jwtToken');

    const Niveles = {
        id: idCurso,
    };

    const response = await fetch('http://localhost/iCraft/Backend/API/ApiNiveles.php/niveles', {
        method: 'PUT',
        headers: {
            'Authorization': `Bearer ${jwtToken}`,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Niveles),
    });

    if (!response.ok) {
        const err = await response.json();
        throw new Error(err.message || 'Error en la respuesta');
    }

    const data = await response.json();
    console.log('Datos del nivel:', data);
    const niveles = Array.isArray(data) ? data : [data];

    localStorage.setItem("instructor_id", niveles[0].instructor_id);
    if (niveles.length === 0) {
        throw new Error('No se encontraron niveles.');
    }

    const cantidadNiveles = niveles.length; 
    const valor = 100 / cantidadNiveles;

    document.getElementById('lista-niveles').innerHTML = niveles.map(nivel => `
        <li data-value="${valor}" data-id="${nivel.id}"  class="w-[500px] flex justify-between items-center p-2 hover:opacity-70">
            <a href="courseVideo.html?id=${nivel.id}&idCurso=${idCurso}" class="btn btn-primary cursor-pointer">
                <div class="flex justify-between items-center gap-5">
                    <img src="data:image/jpeg;base64,${nivel.imagen_curso}" alt="Imagen del curso" class="w-[100px] rounded-lg object-scale-down">
                    <p class="text-gray-500">${nivel.titulo}</p>
                </div>
            </a>
        </li>
    `).join('');

    localStorage.setItem('valor', valor);

}

const ProgresoNivel = async () => {

    const response = await fetch('http://localhost/iCraft/Backend/API/Cursos/APIInscripciones.php/inscripciones', {
        method: 'GET',
        headers: {
            'Authorization': `Bearer ${jwtToken}`,
            'Content-Type': 'application/json',
            'id_inscripcion': idInsc,
        },
    });

    if (!response.ok) {
        const err = await response.json();
        throw new Error(err.message || 'Error en la respuesta');
    }

    const data = await response.json();
    console.log('Progreso del curso:', data);
    return data;
}

document.addEventListener('DOMContentLoaded', async ()  => {
    await getNiveles(idCurso); 
    ProgresoActual = await ProgresoNivel();  

    console.log(ProgresoActual[0].progreso);

    if(ProgresoActual[0].progreso == 100) {

        console.log('Curso completado');
        Swal.fire({
            icon: 'success',
            title: '¡Curso completado!',
            text: '¡Has completado el curso con éxito!',
            confirmButtonText: 'Continuar',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },
            backdrop: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: true
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `../Kardex/kardex.html`;
            }
        });
        
    }
    
    const nivelesElement = document.querySelectorAll('li');
    const niveles = ProgresoActual[0].niveles_completados.split('&');   

    document.querySelectorAll('#lista-niveles li').forEach(li => {
     const value = li.getAttribute('data-value'); console.log(`Value: ${value}`);
        
        for (let nivel of niveles) {
            if (nivel === li.getAttribute('data-id')) {
                li.classList.add('bg-green-100');
                li.classList.add('cursor-pointer');
                li.classList.add('hover:opacity-100');
                li.classList.add('rounded-lg');
            }
        }

    });


}); 

document.querySelectorAll('li').forEach(li => {
    li.addEventListener('click', () => {
        const nivelesElement = document.querySelectorAll('li');

        for (let i = 0; i < nivelesElement.length; i++) {
            if (nivelesElement[i].classList.contains('bg-green-400')) {
                Swal.fire({
                    icon: 'info',
                    title: 'Nivel completado',
                    text: 'Este nivel ya ha sido completado.',
                });
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Nivel no completado',
                    text: 'Este nivel aún no ha sido completado.',
                });
            }
        }
    });
});

document.getElementById('chat').addEventListener('click', () => {
    instructor_id = localStorage.getItem("instructor_id");
    window.location.href = `../Chat/chat.html?idCurso=${idCurso}&idInstructor=${instructor_id}`;
});


