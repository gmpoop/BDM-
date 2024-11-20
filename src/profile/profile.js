function parseJWT(token) {
    // Dividir el token en partes
    const parts = token.split('.');

    // Decodificar la parte de carga útil (payload)
    const payload = JSON.parse(atob(parts[1]));

    return payload;
}

// Obtener el token de localStorage
const jwtToken = localStorage.getItem('jwtToken');

if (jwtToken) {
    const decodedToken = parseJWT(jwtToken);

    // Obtener la información del usuario del token
    const userName = decodedToken.data.nombre; // Cambia "nombre" según lo que tengas en el payload
    const userEmail = decodedToken.data.email;

    // Mostrar en la página
    document.getElementById('userName').innerText = userName;
    document.getElementById('userEmail').innerText = userEmail;
}


// Decodificar el token para obtener el ID del usuario
function parseJWT(token) {
    const parts = token.split('.');
    const payload = JSON.parse(atob(parts[1]));
    return payload;
}
const decodedToken = parseJWT(jwtToken);
const userId = decodedToken.data.id;

// Realizar solicitud a la API para obtener información del usuario
fetch(`http://localhost/BDM-/Backend/API/api.php/user/${userId}`, {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${jwtToken}`,
    },
})
    .then(response => {
        if (!response.ok) {
            throw new Error('Error al obtener la información del usuario');
        }
        return response.json();
    })
    .then(userData => {
        // Mostrar los datos del usuario en la página
        document.getElementById('userName').innerText = userData.nombre_completo;
        document.getElementById('userEmail').innerText = userData.email;
    })
    .catch(error => {
        console.error('Error al conectar con la API:', error);
    });

