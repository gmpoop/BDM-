// Obtener el idCurso de la URL
const urlParams = new URLSearchParams(window.location.search);
let idCurso = urlParams.get('idCurso');  // idCurso es el parámetro que llega en la URL

if (idCurso) {
    // Limpiar el valor de idCurso eliminando el signo $
    idCurso = idCurso.replace('$', '');

    // Construir la URL correctamente usando el idCurso extraído
    const apiUrl = `http://localhost/BDM-/Backend/API/APImensajes.php/DatosRemitente?id=${idCurso}`;

    // Realizar una solicitud GET a la API para obtener los datos del remitente
    fetch(apiUrl)
        .then(response => {
            return response.json();
        })
        .then(data => {
            if (data) {
                // Guardar los datos del remitente en localStorage
                localStorage.setItem('DestinatarioData', JSON.stringify(data));

                // Actualizar la interfaz de usuario con los datos del remitente
                document.getElementById('profile-picture').src = `https://via.placeholder.com/50`; // Imagen por defecto
                document.getElementById('chat-title').textContent = "Instructor " + data.instructor_nombre || 'Instructor';
                document.getElementById('chat-course').textContent = "Del curso " + data.nombre_curso || 'Curso';

                // Iniciales del instructor
                const userInitials = document.createElement('div');
                userInitials.classList.add('bg-[#4821ea]', 'w-12', 'h-12', 'rounded-full', 'flex', 'items-center', 'justify-center', 'text-white', 'font-bold', 'mr-3');
                userInitials.textContent = data.instructor_nombre ? data.instructor_nombre.charAt(0).toUpperCase() : 'N/A';
                document.getElementById('user-initials').textContent = userInitials.textContent;

                // Mostrar las iniciales en lugar de la imagen de perfil
                document.getElementById('user-initials').classList.remove('hidden');
                document.getElementById('profile-picture').classList.add('hidden');
            } else {
                console.error('Datos del remitente no disponibles');
            }
        })
        .catch(error => {
            console.error('Error al obtener los datos del remitente:', error);
        });
} else {
    console.error('No se proporcionó el idCurso en la URL');
}


if (idCurso) {
    // Limpiar el valor de idCurso eliminando el signo $
    idCurso = idCurso.replace('$', '');

    // Construir la URL correctamente usando el idCurso extraído
    const apiUrl = `http://localhost/BDM-/Backend/API/APImensajes.php/chat?curso_id=${idCurso}`;

    // Realizar una solicitud GET a la API para obtener los datos del chat
    fetch(apiUrl)
        .then(response => response.json())  // Convertir la respuesta a JSON
        .then(data => {
            console.log('Datos del chat recibidos:', data);  // Imprimir los datos en la consola
            // Guardar los datos en localStorage
            localStorage.setItem('chatData', JSON.stringify(data)); // Guardar los datos como string JSON
        })
        .catch(error => {
            console.error('Error al obtener los datos del chat:', error);
        });
} else {
    console.error('No se proporcionó el idCurso en la URL');
}

// Escuchar el clic en el botón para enviar el mensaje
document.getElementById('send-message-btn').addEventListener('click', () => {
    const mensaje = document.getElementById('mensaje-input').value;
    const destinatarioData = JSON.parse(localStorage.getItem('DestinatarioData'));  // Usar DestinatarioData
    const ChatData = JSON.parse(localStorage.getItem('chatData'));  // Usar chatData

    // Obtener el token JWT desde localStorage
    const tokenJWT = localStorage.getItem('jwtToken');

    if (!tokenJWT) {
        console.error("No se encontró el token JWT.");
        return;
    }

    // Decodificar el JWT
    const tokenParts = tokenJWT.split('.');
    const payloadBase64 = tokenParts[1];
    const decodedPayload = atob(payloadBase64);
    let tokenData;
    try {
        tokenData = JSON.parse(decodedPayload);
    } catch (error) {
        console.error("Error al decodificar el JWT:", error);
        return;
    }

    // Obtener el id del remitente desde el token decodificado
    const remitenteID = tokenData.data.id;

    // Obtener el chat_id desde el primer elemento de ChatData (es un array)
    const chatData = ChatData[0].id;  // Acceder al primer elemento y obtener el id

    // Crear el objeto con los datos del mensaje
    const mensajeData = {
        remitente_id: remitenteID, // ID del remitente (obtenido desde el token)
        destinatario_id: destinatarioData.instructor_id, // ID del destinatario
        mensaje: mensaje, // El mensaje escrito por el usuario
        chat_id: chatData // El ID del chat
    };

    // Enviar el mensaje a la API
    fetch('http://localhost/BDM-/Backend/API/APImensajes.php/mensaje', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(mensajeData) // Enviar el mensaje como JSON
    })
        .then(response => response.json())
        .then(data => {
            console.log('Mensaje enviado con éxito:', data);
            // Limpiar el campo de texto
            document.getElementById('mensaje-input').value = '';
        })
        .catch(error => {
            console.error('Error al enviar el mensaje:', error);
        });
});
