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
    
    const response = await fetch('http://localhost/BDM-/Backend/API/api.php/niveles', {
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

    if (niveles.length === 0) {
        throw new Error('No se encontraron niveles.');
    }
    console.log(niveles);


    document.getElementById('lista-niveles').innerHTML = niveles.map(nivel => `
    <li class="w-[500px] flex justify-between items-center p-2 hover:opacity-70">
        <a href="courseVideo.html?id=${nivel.id}&idCurso=${idCurso}" class="btn btn-primary cursor-pointer">
         <div class="flex justify-between items-center gap-5">
            <img src="${nivel.video}" alt="" class="rounded-lg">
            <p class="text-gray-500">${nivel.titulo}</p>
        </div>
        </a>
    </li>
    `).join('');

}

document.addEventListener('DOMContentLoaded', getNiveles(idCurso));


