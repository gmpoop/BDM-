// Obtener la URL actual
 url = new URL(window.location.href);

console.log(url); // Salida: http://localhost:3000/profile.html?id=123
// Obtener el valor del parámetro 'id'
const idUrl = url.searchParams.get('id');

const getNivelData = async (idUrl) => {

    console.log('ID:', idUrl);

    const jwtToken = localStorage.getItem('jwtToken');

    const Video = {
        id: idUrl,
    };
    
    const response = await fetch('http://localhost/BDM-/Backend/API/ApiNiveles.php/nivel', {
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
    console.log('Datos del nivel:', data);
    const nivel = data;
    if (nivel === null) {
        throw new Error('No se encontraron usuarios.');
    }
    console.log(nivel);  
    document.getElementById('video').src = nivel.video || '';  // document.getElementById('email-usuario').textContent = nivel.email || '';
    document.getElementById('description_corta_text').textContent = nivel.titulo || 'Masculino';
    document.getElementById('description_larga_text').textContent = nivel.contenido || 'Masculino';
}

document.addEventListener('DOMContentLoaded', getNivelData(idUrl));


