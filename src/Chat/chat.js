// Obtener los parámetros de la URL
const urlParams = new URLSearchParams(window.location.search);
let idCurso = urlParams.get('idCurso');  // idCurso es el parámetro que llega en la URL
let idInstructor = urlParams.get('idInstructor');  // idInstructor es el nuevo parámetro
let userId = urlParams.get('idCliente');  // Obtén tu ID desde la URL

if (!idCurso || !idInstructor || !userId) {
    console.error("ID del curso, ID del instructor o ID del usuario no especificados en la URL.");
} else {
    // Quitar el signo de dólar si existe en idCurso
    idCurso = idCurso.replace('$', '');
    userId = userId.replace('$', '')
    // Quitar el signo de dólar si existe en idInstructor
    idInstructor = idInstructor.replace('$', '');

    // Llamar a la API para obtener los datos del chat con los tres parámetros
    const apiUrl = `http://localhost/BDM-/Backend/API/APImensajes.php/chat?usuario_id=${idInstructor}&curso_id=${idCurso}&usuario_2_id=${userId}`;

    console.log(apiUrl);
    fetch(apiUrl)
        .then((response) => {
            if (!response.ok) {
                throw new Error(`Error en la solicitud: ${response.statusText}`);
            }
            return response.json();  // Convertir la respuesta en JSON
        })
        .then((data) => {
            if (data && Array.isArray(data)) {
                // Guardar la información del chat en localStorage
                localStorage.setItem('chatData', JSON.stringify(data));
                console.log("Información del chat guardada en localStorage:", data);
            } else if (data.message) {
                console.warn("Mensaje de la API: ", data.message);
            } else {
                console.warn("Respuesta inesperada de la API:", data);
            }
        })
        .catch((error) => {
            console.error("Error al llamar a la API:", error);
        });
}

// Función para llenar el frontend del chat
function fillChatData() {
    // Recuperar datos de DataChat desde localStorage
    const chatData = JSON.parse(localStorage.getItem('chatData'));

    // Obtener el ID del usuario actual desde el token almacenado en localStorage
    const currentUserId = obtenerUsuarioIdDesdeJWT(); // Usar la función para obtener el ID del usuario

    // Verificar si existe data válida en localStorage
    if (chatData && Array.isArray(chatData) && chatData.length > 0) {
        // Usaremos el primer chat de ejemplo (puedes adaptar para varios)
        const chat = chatData[0];

        // Determinar si el usuario actual es usuario_1 o usuario_2
        const isUser1 = currentUserId === chat.usuario_1_id;

        // Asignar datos del otro usuario dependiendo de la condición
        const otherUserName = isUser1 ? chat.usuario_2_nombre : chat.usuario_1_nombre;
        const otherUserRole = isUser1 ? chat.usuario_2_rol_nombre : chat.usuario_1_rol_nombre;

        // Obtener elementos del DOM
        const profilePictureContainer = document.getElementById('profile-picture-container');
        const profilePicture = document.getElementById('profile-picture');
        const userInitials = document.getElementById('user-initials');
        const chatTitle = document.getElementById('chat-title');
        const chatCourse = document.getElementById('chat-course');

        // Verificar si el usuario tiene una foto de perfil (ajusta si tienes fotos por usuario)
        if (isUser1 ? chat.usuario_2_imagen : chat.usuario_1_imagen) {
            // Si hay ruta de imagen, mostrar la imagen y ocultar iniciales
            profilePicture.src = isUser1 ? chat.usuario_2_imagen : chat.usuario_1_imagen || "https://via.placeholder.com/50";
            profilePicture.classList.remove('hidden');
            userInitials.classList.add('hidden');
        } else {
            // Si no hay imagen, mostrar iniciales
            const initials = otherUserName
                ? otherUserName.split(' ').map(name => name[0].toUpperCase()).join('')
                : 'N/A';
            userInitials.textContent = initials;
            userInitials.classList.remove('hidden');
            profilePicture.classList.add('hidden');
        }

        // Actualizar el título del chat con el nombre del otro usuario
        chatTitle.textContent = `${otherUserName} (${otherUserRole})`;

        // Actualizar el curso asociado al chat
        chatCourse.textContent = `Curso: ${chat.nombre_curso}`;
    } else {
        console.error("No se encontró información válida en localStorage para llenar el chat.");
    }
}
// Llamar a la función cuando el DOM esté completamente cargado
document.addEventListener('DOMContentLoaded', fillChatData);

// Función para enviar un mensaje
function enviarMensaje() {
    const mensajeInput = document.getElementById('mensaje-input');
    const mensaje = mensajeInput.value.trim();

    if (!mensaje) {
        alert("Escribe un mensaje antes de enviar.");
        return;
    }

    const chatData = JSON.parse(localStorage.getItem('chatData'));
    const tokenString = localStorage.getItem('jwtToken');

    if (!chatData || !tokenString) {
        alert("Faltan datos necesarios para enviar el mensaje.");
        return;
    }

    let tokenData;
    try {
        tokenData = decodeJWT(tokenString);
    } catch (error) {
        console.error("Error al decodificar el token:", error);
        alert("Token inválido o mal formado.");
        return;
    }

    const remitenteId = tokenData?.data?.id;
    const chatId = chatData[0]?.chat_id;

    if (!chatId || !remitenteId) {
        alert("Faltan parámetros en el chat o el token.");
        return;
    }

    const destinatarioId = remitenteId === chatData[0]?.usuario_1_id
        ? chatData[0]?.usuario_2_id
        : chatData[0]?.usuario_1_id;

    const mensajeData = {
        remitente_id: remitenteId,
        destinatario_id: destinatarioId,
        mensaje: mensaje,
        chat_id: chatId
    };

    console.log("Datos enviados a la API:", mensajeData);

    fetch('http://localhost/BDM-/Backend/API/APImensajes.php/mensaje', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(mensajeData)
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Mensaje enviado correctamente.");
                mensajeInput.value = '';
            } else {
                console.error(data.message || "Error al enviar el mensaje.");
            }
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
        });
}

// Asignar el evento al botón
document.getElementById('send-message-btn').addEventListener('click', enviarMensaje, cargarMensajes);

// Enviar mensaje al presionar Enter en el input
document.getElementById('mensaje-input').addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        enviarMensaje();
    }
});

// Decodificar el JWT y obtener el payload
function decodeJWT(token) {
    const payloadBase64 = token.split('.')[1]; // Extraer la segunda parte (payload)
    const payloadJson = atob(payloadBase64); // Decodificar Base64
    return JSON.parse(payloadJson); // Convertir a objeto JSON
}

// Obtener el usuario_id desde el JWT
function obtenerUsuarioIdDesdeJWT() {
    const jwtToken = localStorage.getItem('jwtToken'); // Asegurarse de que el JWT está en localStorage

    if (jwtToken) {
        try {
            const decodedToken = decodeJWT(jwtToken); // Decodificar el token para obtener el usuario_id
            return decodedToken.data.id; // Asegúrate de que 'data.id' es la propiedad correcta en tu payload
        } catch (error) {
            console.error('Error al decodificar el JWT:', error);
            return null;
        }
    } else {
        console.error('No se encontró el JWT en el localStorage');
        return null;
    }
}

// Obtener el chat_id desde localStorage
function obtenerChatIdDesdeLocalStorage() {
    const chatData = JSON.parse(localStorage.getItem('chatData'));

    if (chatData && Array.isArray(chatData) && chatData.length > 0) {
        return chatData[0].chat_id; // Asegúrate de que 'chat_id' está en la estructura
    } else {
        console.error('No se encontró chatData en el localStorage');
        return null;
    }
}

// Cargar los mensajes del chat
function cargarMensajes() {
    const chat_id = obtenerChatIdDesdeLocalStorage(); // Obtener el chat_id desde localStorage
    const usuario_id = obtenerUsuarioIdDesdeJWT(); // Obtener el usuario_id desde el JWT
    if (chat_id) {
        fetch(`http://localhost/BDM-/Backend/API/APImensajes.php/allmensajes?chat_id=${chat_id}`, {
            method: 'GET',
            headers: {
                // Aquí podrías agregar headers de autenticación si es necesario
            }
        })
            .then(response => response.json())  // Convertir la respuesta en JSON
            .then(data => {
                // Verificamos si la respuesta es un array de mensajes
                if (Array.isArray(data) && data.length > 0) {
                    // Limpiar el área del chat antes de cargar los nuevos mensajes
                    const chatArea = document.querySelector('.chat-area');
                    chatArea.innerHTML = '';  // Limpiar el área de mensajes

                    // Recorrer los mensajes y mostrarlos
                    data.forEach(mensaje => {
                        const mensajeDiv = document.createElement('div');
                        mensajeDiv.classList.add('message');

                        // Verificar si el mensaje lo envió el usuario actual (si es necesario mostrar esa distinción)
                        if (mensaje.remitente_id === usuario_id) {
                            // Si lo envió el usuario, es un mensaje enviado
                            mensajeDiv.classList.add('sent');
                        } else {
                            // Si lo envió otro usuario, es un mensaje recibido
                            mensajeDiv.classList.add('received');
                        }

                        // Agregar contenido al mensaje
                        mensajeDiv.innerHTML = `
                        <div class="message-text">${mensaje.mensaje}</div>
                        <div class="message-time">${new Date(mensaje.fecha_envio).toLocaleTimeString()}</div>
                    `;

                        // Agregar el mensaje al área del chat
                        chatArea.appendChild(mensajeDiv);
                    });
                } else {
                    console.log('No se encontraron mensajes o la respuesta de la API está vacía');
                }
            })
            .catch(error => {
                console.error('Error al obtener los mensajes:', error);
            });
    } else {
        console.error('No se encontró el chat_id en el localStorage');
    }
}

// Llamar a la función para cargar los mensajes al cargar la página
cargarMensajes();