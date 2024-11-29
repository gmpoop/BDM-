
const paymentMethod = document.getElementById('payment-method');
const formTarjeta = document.getElementById('form-tarjeta');
const formPayPal = document.getElementById('form-paypal');
const formTransferencia = document.getElementById('form-transferencia');
const termsAnConditions = document.getElementById('terms');

paymentMethod.addEventListener('change', function () {
    // Ocultar todos los formularios
    formTarjeta.classList.add('hidden');
    formPayPal.classList.add('hidden');
    formTransferencia.classList.add('hidden');

    // Mostrar formulario correspondiente al método de pago seleccionado
    switch (paymentMethod.value) {
        case 'tarjeta':
            formTarjeta.classList.remove('hidden');
            break;
        case 'paypal':
            formPayPal.classList.remove('hidden');
            break;
        case 'transferencia':
            formTransferencia.classList.remove('hidden');
            break;
    }
});

// function validarFormularioTarjetas() {

//     const numeroTarjeta = document.getElementById("numeroTarjeta").value.trim();
//     const fechaExpiracion = document.getElementById("fechaExpiracion").value.trim();
//     const cvv = document.getElementById("cvv").value.trim();

//     console.log("Esta entrando");
//     if (numeroTarjeta === "" || !/^\d{16}$/.test(numeroTarjeta)) {
//         Swal.fire({
//             icon: 'error',
//             title: 'Error',
//             text: 'Por favor, ingrese un número de tarjeta válido de 16 dígitos.',
//             confirmButtonText: 'Continuar'
//         })
//         return false;
//     }
//     if (fechaExpiracion === "" || !/^\d{2}\/\d{2}$/.test(fechaExpiracion)) {
//         Swal.fire({
//             icon: 'error',
//             title: 'Error',
//             text: 'Por favor, ingrese una fecha de expiración válida (MM/YY).'
//         });
//         return false;
//     }
//     if (cvv === "" || !/^\d{3}$/.test(cvv)) {
//         Swal.fire({
//             icon: 'error',
//             title: 'Error',
//             text: 'Por favor, ingrese un CVV válido de 3 dígitos.'
//         });
//         return false;
//     }
//     Swal.fire({
//         icon: 'success',
//         title: 'Pago realizado',
//         text: 'Su pago con tarjeta de crédito ha sido procesado exitosamente.'
//     });

// }

// function validarFormularioPaypal() {
//     const emailPaypal = document.getElementById("emailPaypal").value.trim();
//     const passwordPaypal = document.getElementById("passwordPaypal").value.trim(); // Asegúrate de tener este elemento en tu HTML

//     if (emailPaypal === "" || !/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(emailPaypal)) {
//         Swal.fire({
//             icon: 'error',
//             title: 'Error',
//             text: 'Por favor, ingrese un correo electrónico válido.'
//         });
//         return false;
//     }
//     if (passwordPaypal === "" || passwordPaypal.length < 6) {
//         Swal.fire({
//             icon: 'error',
//             title: 'Error',
//             text: 'Por favor, ingrese una contraseña válida de al menos 6 caracteres.'
//         });
//         return false;
//     }
//     Swal.fire({
//         icon: 'success',
//         title: 'Pago realizado',
//         text: 'Su pago con PayPal ha sido procesado exitosamente.'
//     });
//     return true;
// }

// function validarTransferencia() {
//     const nombreBanco = document.getElementById("nombreBanco").value.trim();
//     const numCuenta = document.getElementById("numCuenta").value.trim();

//     if (nombreBanco === "" || nombreBanco.length < 3) {
//         Swal.fire({
//             icon: 'error',
//             title: 'Error',
//             text: 'Por favor, ingrese el nombre del banco con al menos 3 caracteres.'
//         });
//         return false;
//     }

//     if (numCuenta === "" || !/^\d{10,20}$/.test(numCuenta)) {
//         Swal.fire({
//             icon: 'error',
//             title: 'Error',
//             text: 'Por favor, ingrese un número de cuenta válido (10-20 dígitos).'
//         });
//         return false;
//     }

//     Swal.fire({
//         icon: 'success',
//         title: 'Pago realizado',
//         text: 'Su pago mediante transferencia bancaria ha sido procesado exitosamente.'
//     });
//     return true;
// }

document.addEventListener("DOMContentLoaded", () => {
    const carrito = JSON.parse(localStorage.getItem("carrito")) || []; // Recuperamos los cursos del carrito
    const carritoContainer = document.querySelector("method ul");
    const totalDisplay = document.querySelector("#totalDisplay");
    const asideContainer = document.querySelector("aside .p-4");
});

function agregarAlCarrito() {

    event.preventDefault();

    let complete = false;
    let terms = false;

    if (!termsAnConditions.checked) {

        Swal.fire({
            title: '¡Aviso! ',
            text: 'Tiene que aceptar los terminos y condiciones para poder continuar',
            icon: 'warning',
            confirmButtonText: 'Ok',
        })

        terms = false;
    }
    else
        terms = true;



    if (paymentMethod.value != "") {
        switch (paymentMethod.value) {
            case 'tarjeta':
                console.log("Eligió tarjeta");
                //complete = validarFormularioTarjetas();
                break;
            case 'paypal':
                console.log("Eligió PayPal");
                // complete = validarFormularioPaypal();
                break;
            case 'transferencia':
                console.log("Eligió transferencia");
                // complete = validarTransferencia();
                break;
        }

  
    } else if (terms) {
        Swal.fire({
            title: '¡Aviso! ',
            text: 'Por favor ingresa un metodo de pago previamente',
            icon: 'warning',
            confirmButtonText: 'Ok',
        })
    }


}

// Asegúrate de que el DOM esté completamente cargado antes de asociar eventos
document.addEventListener("DOMContentLoaded", () => {
    const botonPago = document.getElementById("proceed-payment");

    // Verifica si el botón existe antes de asignarle el evento
    if (botonPago) {
        botonPago.addEventListener("click", async (event) => {
            event.preventDefault(); // Detiene el comportamiento predeterminado del formulario
            console.log("Botón de pago clickeado");
            
            // Aquí puedes llamar directamente a realizarCompra
            await realizarCompra();
        });
    }
});

async function realizarCompra() {
    // Recuperar el carrito del localStorage
    const carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    if (carrito.length === 0) {
        Swal.fire({
            title: "Carrito vacío",
            text: "No hay cursos en el carrito para comprar.",
            icon: "info",
            confirmButtonText: "Ok",
        });
        return;
    }

    // Obtener el comprador_id desde el JWT
    const jwtToken = localStorage.getItem("jwtToken");
    if (!jwtToken) {
        Swal.fire({
            title: "Error",
            text: "No se encontró el token de usuario. Por favor, inicia sesión.",
            icon: "error",
            confirmButtonText: "Ok",
        });
        return;
    }

    // Decodificar el JWT para obtener el comprador_id
    let compradorId;
    try {
        const decodedJWT = jwt_decode(jwtToken); // Decodificar JWT
        compradorId = decodedJWT.data.id; // Ajustar según la estructura del JWT
    } catch (error) {
        Swal.fire({
            title: "Error de token",
            text: "Hubo un problema al decodificar el token. Intenta nuevamente.",
            icon: "error",
            confirmButtonText: "Ok",
        });
        return;
    }

    // Obtener la forma de pago seleccionada
    const formaPago = document.querySelector("#payment-method").value;
    if (!formaPago) {
        Swal.fire({
            title: "Error",
            text: "Por favor selecciona una forma de pago.",
            icon: "error",
            confirmButtonText: "Ok",
        });
        return;
    }

    // Iterar por cada curso en el carrito y realizar la compra
    for (const cursoObj of carrito) {
        try {
            // Obtener detalles del curso
            const curso = await obtenerCursoPorId(cursoObj.id);
            if (!curso) {
                console.error(`No se pudo obtener detalles del curso con ID ${cursoObj.id}`);
                continue;
            }

            // Preparar los datos para la API
            const ventaData = {
                curso_id: curso.id_curso,
                usuario_id: curso.instructor_id, // ID del usuario que subió el curso
                comprador_id: compradorId, // ID del usuario que realiza la compra
                forma_pago: formaPago,
                ingreso: parseFloat(curso.costo), // Precio unitario del curso
            };

            console.log("Enviando venta:", ventaData);

            // Enviar datos a la API
            const response = await fetch("http://localhost/BDM-/Backend/API/Cursos/APIventas.php/ventas", {
                method: "POST",
                headers: {
                    "Authorization": `Bearer ${jwtToken}`, // Enviar el token en el header
                    "Content-Type": "application/json", // Especificar formato JSON
                },
                body: JSON.stringify(ventaData),
            });

            const result = await response.json();
            if (response.ok) {
                console.log(`Compra realizada para el curso ID ${curso.id_curso}:`, result);
            } else {
                console.error(`Error al realizar la compra para el curso ID ${curso.id_curso}:`, result);
            }
        } catch (error) {
            console.error(`Error procesando el curso ID ${cursoObj.id}:`, error);
        }
    }

    // Limpiar el carrito tras la compra exitosa
    carrito.length = 0;
    localStorage.setItem("carrito", JSON.stringify(carrito));
    renderizarCarrito();

    Swal.fire({
        title: "¡Compra realizada!",
        text: "Se han procesado todos los cursos en el carrito.",
        icon: "success",
        confirmButtonText: "Ok",
    });
}

// Definir las funciones fuera del DOMContentLoaded para que sean globales
const obtenerCursoPorId = async (idCurso) => {
    const jwtToken = localStorage.getItem('jwtToken');  // Recuperamos el token JWT desde localStorage
    console.log('JWT Token:', jwtToken); // Verifica si el token está presente

    try {
        const response = await fetch(`http://localhost/BDM-/Backend/API/Cursos/APICursos.php/cursobyid?curso_id=${idCurso}`, {
            method: 'GET',
            headers: {
                'Authorization': `Bearer ${jwtToken}`, // Añadimos el token JWT en el header
                'Content-Type': 'application/json'  // Aseguramos que la API espera JSON
            }
        });

        const data = await response.json();
        console.log('Data recibida:', data); // Verifica la respuesta de la API

        if (data.length > 0) {
            return data[0]; // Regresamos el primer curso encontrado
        } else {
            console.error("Curso no encontrado");
            return null;
        }
    } catch (error) {
        console.error("Error al obtener el curso:", error);
        return null;
    }
};

// Función para renderizar el carrito
const renderizarCarrito = async () => {
    console.log('Renderizando carrito...');  // Depuración
    const carrito = JSON.parse(localStorage.getItem("carrito")) || [];  // Recuperamos los cursos del carrito
    const carritoContainer = document.querySelector("method ul");
    const totalDisplay = document.querySelector("#totalDisplay");
    const asideContainer = document.querySelector("aside .p-4");

    if (carrito.length === 0) {
        carritoContainer.innerHTML = "<li>No hay cursos en tu carrito</li>";
        totalDisplay.textContent = "0.00";
        asideContainer.innerHTML = "";
        return;
    }

    // Borramos el contenido actual del carrito
    carritoContainer.innerHTML = "";
    let total = 0; // Reiniciamos el total

    // Recorremos los objetos del carrito
    for (const cursoObj of carrito) {
        const curso = await obtenerCursoPorId(cursoObj.id); // Accedemos solo al id

        // Depuración: verifique el valor de curso.id_curso
        console.log('Curso:', curso);  // Verifica si curso tiene el id_curso correcto

        if (curso) {
            // Creamos el HTML del curso, usamos curso.id_curso
            const cursoHTML = `
            <li class="flex justify-between items-center py-4" data-curso-id="${curso.id_curso}">
                <div class="flex flex-col items-start gap-2">
                    <h3 class="text-lg font-bold text-gray-700">${curso.titulo}</h3>

                    <div class="bg-gray-400 rounded-lg p-2 shadow-lg flex flex-wrap max-w-[350px]">
                        <p class="text-gray-900 text-xs font-semibold">${curso.categoria_nombre}</p>
                    </div>
                </div>
                <p class="text-lg font-bold text-[#4821ea]">$<span class="price">${curso.costo}</span></p>
                    <button class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 delete-btn" data-id="${curso.id_curso}">
                        Eliminar
                    </button>

            </li>
        `;

            // Insertamos el curso en el carrito
            carritoContainer.innerHTML += cursoHTML;

            // Sumamos el precio del curso
            total += parseFloat(curso.costo);
        }
    }

    // Mostramos el total
    totalDisplay.textContent = total.toFixed(2);

    // Renderizamos todos los cursos en el aside
    asideContainer.innerHTML = "<h2 class='text-2xl font-bold text-[#4821ea]'>Cursos en tu carrito</h2>";
    carrito.forEach(async (cursoObj) => {
        const curso = await obtenerCursoPorId(cursoObj.id);
        if (curso) {
            asideContainer.innerHTML += `
            <div class="bg-gray-100 p-4 mb-2">
                <h3 class="text-lg font-bold text-[#4821ea]">${curso.titulo}</h3>
                <p class="text-gray-600">${curso.descripcion}</p>
                <p class="text-lg font-bold text-[#4821ea]">${curso.categoria_nombre}</p>
            </div>`;
        }
    });
};

// Manejador de click para eliminar curso
document.addEventListener("DOMContentLoaded", () => {
    const carritoContainer = document.querySelector("method ul");

    carritoContainer.addEventListener('click', (e) => {
        if (e.target && e.target.classList.contains('delete-btn')) {
            // Obtener el id del curso desde el atributo 'data-id' en el botón
            const cursoId = e.target.getAttribute('data-id'); // Usamos data-id directamente
            console.log('Eliminando curso con ID:', cursoId);  // Depuración
            eliminarDelCarrito(cursoId);
        }
    });

    // Inicializamos el carrito
    renderizarCarrito();
});

// Eliminar un curso del carrito
const eliminarDelCarrito = (idCurso) => {
    const carrito = JSON.parse(localStorage.getItem("carrito")) || [];
    const idCursoNum = parseInt(idCurso, 10);  // Convertimos el ID a número
    const index = carrito.findIndex(curso => curso.id === idCursoNum); // Comparar con curso.id

    if (index !== -1) {
        carrito.splice(index, 1); // Eliminamos el curso
        localStorage.setItem("carrito", JSON.stringify(carrito)); // Actualizamos el carrito en el localStorage
        renderizarCarrito(); // Volvemos a renderizar el carrito
    } else {
        console.log("No se encontró el curso con ID:", idCursoNum);  // Depuración
    }
};
