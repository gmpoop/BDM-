// Obtener la URL actual
url = new URL(window.location.href);

console.log(url); // Salida: http://localhost:3000/profile.html?id=123
// Obtener el valor del parámetro 'id'
const idVideo = url.searchParams.get('id');
const idInsc = url.searchParams.get('id_inscripcion');

const jwtToken = localStorage.getItem('jwtToken');
const progreso = localStorage.getItem('valor');


const getNivelData = async (idVideo) => {

    console.log('ID:', idVideo);

    const jwtToken = localStorage.getItem('jwtToken');

    const Video = {
        id: idVideo,
    };

    const response = await fetch('http://localhost/iCraft/Backend/API/ApiNiveles.php/nivel', {
        method: 'PUT',
        headers: {
            'Authorization': `Bearer ${jwtToken}`,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Video),
    });

    if (!response.ok) {
        const err = await response.json();
        throw new Error(err.message || 'Error en la respuesta');
    }

    const data = await response.json();
    const nivel = data;
    if (nivel === null) {
        throw new Error('No se encontraron usuarios.');
    }

    document.getElementById('video').src = nivel.video || '';  // document.getElementById('email-usuario').textContent = nivel.email || '';
    document.getElementById('description_corta_text').textContent = nivel.titulo || 'Masculino';
    document.getElementById('description_larga_text').textContent = nivel.contenido || 'Masculino';

}


const NivelCompletado = async (idNivel) => {

    const Nivel = {
        id: idInsc,
        usuario_id: 8,
        progreso: progreso,
        niveles_completados: idNivel,

    };

    console.log('Nivel:', Nivel);

    const response = await fetch('http://localhost/iCraft/Backend/API/Cursos/APIInscripciones.php/inscripcion', {
        method: 'PUT',
        headers: {
            'Authorization': `Bearer ${jwtToken}`,
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(Nivel),
    });

    if (!response.ok) {
        const err = await response.json();
        throw new Error(err.message || 'Error en la respuesta');
    }

    const data = await response.json();




}

document.addEventListener('DOMContentLoaded', getNivelData(idVideo));

document.addEventListener('DOMContentLoaded', () => {


    document.getElementById('video_completado').addEventListener('click', async () => {


        try {
            const response = await NivelCompletado(idVideo);

            Swal.fire({
                icon: 'success',
                title: '¡Nivel compelado!',
                text: 'El nivel se ha completado exitosamente.',
            });

        }
        catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message,
            });
        }

    });

});


