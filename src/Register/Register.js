
const PORT = "http://localhost/iCraft/Backend/API/";

const API_KEY = "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"

// Configurar la solicitud a SendGrid API
const url = 'api.php/send_email';

const SendEmail = async () => {

    const nombre_completo = document.getElementById('nombre_completo').value;
    const email = document.getElementById('email').value;

    data = {
        nombre_completo: nombre_completo,
        email: email
    }

    try {
        const response = await fetch(PORT + url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${API_KEY}`
            },
            body: JSON.stringify(data)
        });
    
        if (response.status >= 200 && response.status < 300) {
            return true;
        } else {
            const errorData = await response.json();
            console.error('Error:', errorData);
            return false;
        }
    } catch (error) {
        console.error('Error:', error);
        return false;
    }
}

function sendData(data) {
    console.log(data);
    fetch('http://localhost/iCraft/Backend/API/api.php/register', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data),
    })
        .then(async response => {
            if (!response.ok) {
                const text = await response.text();
                throw new Error("Error en la respuesta: " + text);
            }
            const contentType = response.headers.get("content-type");
            if (!contentType || !contentType.includes("application/json")) {
                const text = await response.text();
                throw new Error("Respuesta no JSON: " + text);
            }
            return response;
        })
        .then(response => {
            if (response.status === 201) { 
                const data = SendEmail()       
                if(data){
                    Swal.fire({
                        icon: 'success',
                        title: 'Usuario registrado',
                        text: 'El usuario ha sido registrado exitosamente.',
                    });
                    document.getElementById('registerForm').reset();
                }
                else{
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'No se pudo registrar el usuario.',
                    });
                }

            } else {

                throw new Error("Error en la respuesta: " + response.status);
            }
        })

        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Hubo un error al conectar con la API.',
            });
        });
}


document.getElementById('registerForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Evitar envío estándar del formulario

    // Obtener los valores de los campos
    const nombreCompleto = document.getElementById('nombre_completo').value.trim();
    const email = document.getElementById('email').value.trim();
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password-confirm').value;
    const fechaNacimiento = document.getElementById('fecha_nacimiento').value;
    const genero = document.getElementById('gender').value;
    const fileInput = document.getElementById('foto');
    const rol_id = document.getElementById('rol-user').value;


    // Validar que ningún campo esté vacío
    if (!nombreCompleto || !email || !password || !confirmPassword || !fechaNacimiento || !genero) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Todos los campos son obligatorios.',
        });
        return; // Salir si algún campo está vacío
    }

    // Validar formato de correo electrónico
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'El correo electrónico no es válido.',
        });
        return; // Salir si el correo no es válido
    }

    // Validar fecha de nacimiento
    const today = new Date();
    const birthDate = new Date(fechaNacimiento);
    if (birthDate > today) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La fecha de nacimiento no puede ser mayor a la fecha actual.',
        });
        return; // Salir si la fecha de nacimiento es mayor a hoy
    }

    const passwordPattern = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
    // Si hay nueva contraseña, validar
    if (password && !passwordPattern.test(password)) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'La nueva contraseña debe tener al menos 8 caracteres, incluir una mayúscula, un número y un carácter especial.',
        });
        return;
    }

    // Validar que las contraseñas coincidan
    if (password !== confirmPassword) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Las contraseñas no coinciden.',
        });
        return; // Salir si las contraseñas no coinciden
    }

    // Crear un objeto con los datos del formulario
    const formData = {
        nombre_completo: nombreCompleto,
        email: email,
        contraseña: password, // Usar la contraseña ingresada
        genero: genero,
        fecha_nacimiento: fechaNacimiento,
        rol_id: rol_id,
    };

    // Manejar la imagen como base64
    if (fileInput.files.length > 0) {
        const reader = new FileReader();
        reader.onloadend = function () {
            formData.foto = reader.result; // Convertir la imagen a base64
            sendData(formData); // Enviar los datos
        };
        reader.readAsDataURL(fileInput.files[0]); // Leer el archivo como Data URL
    } else {
        formData.foto = null; // Si no hay foto, establecer en null
        sendData(formData); // Enviar los datos
    }
});