<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Compras - Cursos</title>
    <link href="../output.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Incluir SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<div>
    <?php include 'C:\xampp\htdocs\BDM-\src\navbar_dash.php'; ?>
</div>

<body class="bg-gray-100 text-gray-800">
    <!-- Contenedor Principal -->

        <div class="container mx-auto p-6">
            <!-- Título del Menú de Compras -->
            <h1 class="text-4xl font-bold text-center text-[#4821ea] mb-8">Menú de Compras</h1>

            <!-- Carrito de Compras -->
            <main class="w-auto flex justify-between ">
                <method class="w-full mt-12 bg-white shadow-lg rounded-lg p-6">
                    <h2 class="text-2xl font-bold text-[#4821ea] mb-4">Tu Carrito de Compras</h2>
                    <ul class="divide-y divide-gray-300">
                        
                    <!-- Aqui van los elementos del carrito -->
                    <?php include 'ulist.php'; ?>

                    </ul>
                    <div class="flex justify-between items-center mt-6">
                        <span class="text-xl font-bold">Total:</span>
                        <p class="text-2xl font-bold text-[#4821ea]S">$<span id="totalDisplay" class="total">49.99</span></p>
                    </div>
        
                    <!-- Verificación del Método de Pago -->
                    <div class="mt-8">
                        <h2 class="text-2xl font-bold text-[#4821ea] mb-4">Método de Pago</h2>
                        <form id="payment-form" name="form">
                            <div class="mb-4">
                                <label for="payment-method" class="block text-lg font-semibold text-gray-700">Selecciona un método de pago:</label>
                                <select id="payment-method" class="w-full bg-gray-200 border border-gray-300 text-gray-700 py-2 px-4 rounded mt-2" >
                                    <option value="">-- Selecciona una opción --</option>
                                    <option value="tarjeta">Tarjeta de Crédito/Débito</option>
                                    <option value="paypal">PayPal</option>
                                    <option value="transferencia">Transferencia Bancaria</option>
                                </select>
                            </div>
        
                            <!-- Formulario para Tarjeta de Crédito/Débito -->
                            <div id="form-tarjeta" class="hidden">
                                <h3 class="text-xl font-bold text-[#4821ea] mb-2">Detalles de Tarjeta de Crédito/Débito</h3>
                                <div class="mb-4">
                                    <input id="nombreTarjeta" type="text" placeholder="Nombre en la tarjeta" class="w-full bg-gray-200 border border-gray-300 text-gray-700 py-2 px-4 rounded" >
                                </div>
                                <div class="mb-4">
                                    <input id="numeroTarjeta" type="text" placeholder="Número de tarjeta" class="w-full bg-gray-200 border border-gray-300 text-gray-700 py-2 px-4 rounded" >
                                </div>
                                <div class="flex space-x-4 mb-4">
                                    <input id="fechaExpiracion" type="text" placeholder="MM/AA" class="w-1/2 bg-gray-200 border border-gray-300 text-gray-700 py-2 px-4 rounded" >
                                    <input id="cvv" type="text" placeholder="CVC" class="w-1/2 bg-gray-200 border border-gray-300 text-gray-700 py-2 px-4 rounded" >
                                </div>
                            </div>
        
                            <!-- Formulario para PayPal -->
                            <div id="form-paypal" class="hidden">
                                <h3 class="text-xl font-bold text-[#4821ea] mb-2">Detalles de PayPal</h3>
                                <div class="mb-4">
                                    <input id="emailPaypal" type="email" placeholder="Correo Electrónico de PayPal" class="w-full bg-gray-200 border border-gray-300 text-gray-700 py-2 px-4 rounded" >
                                </div>
                            </div>
        
                            <!-- Formulario para Transferencia Bancaria -->
                            <div id="form-transferencia" class="hidden">
                                <h3 class="text-xl font-bold text-[#4821ea] mb-2">Detalles de Transferencia Bancaria</h3>
                                <div class="mb-4">
                                    <input id="nombreBanco" type="text" placeholder="Nombre del Banco" class="w-full bg-gray-200 border border-gray-300 text-gray-700 py-2 px-4 rounded" >
                                </div>
                                <div class="mb-4">
                                    <input id="numCuenta" type="text" placeholder="Número de Cuenta" class="w-full bg-gray-200 border border-gray-300 text-gray-700 py-2 px-4 rounded" >
                                </div>
                            </div>
        
                            <div class="mb-4">
                                <input  type="checkbox" id="terms" class="mr-2 leading-tight" >
                                <label for="terms" class="text-gray-700">Acepto los <a href="#" class="text-[#4821ea]">términos y condiciones</a></label>
                            </div>
                            <button id="proceed-payment" type="button" class="mt-6 w-full bg-[#4821ea] text-white py-2 px-4 rounded hover:bg-[#3d1bc8]" onclick="agregarAlCarrito(event)">
                                Proceder al Pago
                            </button>
                        </form>
                    </div>
                </method>
                <!-- Aside -->
                <aside class="w-auto mt-12 p-6 -translate-y-6">
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                        <div class="p-4">
                            <h2 class="text-2xl font-bold text-[#4821ea]">Desarrollo Web</h2>
                            <p class="text-gray-600 mt-2">Aprende a crear sitios web interactivos con las últimas tecnologías.</p>
                            <button class="mt-4 bg-[#4821ea] text-white py-2 px-4 rounded hover:bg-[#3d1bc8]">
                                Ver Cursos
                            </button>
                        </div>
                    </div>
                </aside>
            </main>

        </div>
    


    <script>   

        const paymentMethod = document.getElementById('payment-method');
        const formTarjeta = document.getElementById('form-tarjeta');
        const formPayPal = document.getElementById('form-paypal');
        const formTransferencia = document.getElementById('form-transferencia');
        const termsAnConditions = document.getElementById('terms');

        document.addEventListener('DOMContentLoaded', function () {

            total();

        });


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

        function verCurso(productId) {
            // Redirigir al usuario a la página deseada con el parámetro productId
            window.location.href = "/BDM-/src/templateCourse/course.php?id=" + encodeURIComponent(productId);
        }

    function total(){

        const totalDisplay = document.getElementById('totalDisplay');
        const prices = document.querySelectorAll('.price');

            const sumTotal = () => {
                let totalPrices = 0;

                prices.forEach((price) => {

                    console.log(price.textContent)

                    totalPrices += parseFloat(price.textContent); 
                });

                return totalPrices;
            };

            if (totalDisplay) {
                totalDisplay.textContent = ` ${sumTotal().toFixed(2)}`; // Mostrar el total formateado a 2 decimales
            }

    } 

    function agregarAlCarrito() {

        event.preventDefault();

        let complete = false; 
        let terms = false;

        if(!termsAnConditions.checked){

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
            
       

        if(paymentMethod.value != "") {
            switch (paymentMethod.value) {
                case 'tarjeta':
                    console.log("Eligió tarjeta");
                    complete = validarFormularioTarjetas();
                    break;
                case 'paypal':
                    console.log("Eligió PayPal");
                    complete = validarFormularioPaypal();
                    break;
                case 'transferencia':
                    console.log("Eligió transferencia");
                    complete = validarTransferencia();
                    break;
            }

            if (complete) {
                Swal.fire({
                    title: 'Curso Agregado al Carrito',
                    text: 'Has agregado el curso de Desarrollo Web Completo a tu carrito.',
                    icon: 'success',
                    confirmButtonText: 'Continuar'
                });
                return true;
            } 
        } else if (terms){
            Swal.fire({
            title: '¡Aviso! ',
            text: 'Por favor ingresa un metodo de pago previamente',
            icon: 'warning',
            confirmButtonText: 'Ok',
        })
        }

        
    }

    function eliminarDelCarrito(productId) {
        // Confirmar la eliminación usando SweetAlert2
        Swal.fire({
            title: '¡Chanfles!',
            text: '¿Deseas eliminar este curso de tu carrito?',
            icon: 'error',
            showCancelButton: true, // Mostrar botón de cancelar
            confirmButtonText: 'Sí, eliminarlo',
            cancelButtonText: 'Cancelar'
        }).then(result => {
            // Si el usuario confirma la eliminación
            if (result.isConfirmed) {
                // Enviar la solicitud de eliminación al servidor
                fetch('eliminar_producto.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'id': productId
                    })
                })
                .then(response => response.text())
                .then(result => {
                    // Manejar la respuesta del servidor
                    if (result.trim() === 'success') {
                        // Eliminar el elemento del DOM
                        const button = document.querySelector(`button[onclick='eliminarDelCarrito(${productId})']`);
                        if (button) {
                            button.closest('li').remove();
                        }
                        Swal.fire('Eliminado!', 'El curso ha sido eliminado de tu carrito.', 'success');
                    } else {
                        Swal.fire('Error', 'Error al eliminar el producto.', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Error al eliminar el producto.', 'error');
                });
            }
        });
    }
    
    function validarFormularioTarjetas() {

        const numeroTarjeta = document.getElementById("numeroTarjeta").value.trim();
        const fechaExpiracion = document.getElementById("fechaExpiracion").value.trim();
        const cvv = document.getElementById("cvv").value.trim();

        console.log("Esta entrando");
        if (numeroTarjeta === "" || !/^\d{16}$/.test(numeroTarjeta)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, ingrese un número de tarjeta válido de 16 dígitos.',
                confirmButtonText: 'Continuar'
            })
            return false;
        }
        if (fechaExpiracion === "" || !/^\d{2}\/\d{2}$/.test(fechaExpiracion)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, ingrese una fecha de expiración válida (MM/YY).'
            });
            return false;
        }
        if (cvv === "" || !/^\d{3}$/.test(cvv)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, ingrese un CVV válido de 3 dígitos.'
            });
            return false;
        }
        Swal.fire({
            icon: 'success',
            title: 'Pago realizado',
            text: 'Su pago con tarjeta de crédito ha sido procesado exitosamente.'
        });
    
    }

    function validarFormularioPaypal() {
        const emailPaypal = document.getElementById("emailPaypal").value.trim();
        const passwordPaypal = document.getElementById("passwordPaypal").value.trim(); // Asegúrate de tener este elemento en tu HTML

        if (emailPaypal === "" || !/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(emailPaypal)) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, ingrese un correo electrónico válido.'
            });
            return false;
        }
        if (passwordPaypal === "" || passwordPaypal.length < 6) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Por favor, ingrese una contraseña válida de al menos 6 caracteres.'
            });
            return false;
        }
        Swal.fire({
            icon: 'success',
            title: 'Pago realizado',
            text: 'Su pago con PayPal ha sido procesado exitosamente.'
        });
        return true;
    }

    function validarTransferencia() {
        const nombreBanco = document.getElementById("nombreBanco").value.trim();
            const numCuenta = document.getElementById("numCuenta").value.trim();

            if (nombreBanco === "" || nombreBanco.length < 3) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor, ingrese el nombre del banco con al menos 3 caracteres.'
                });
                return false;
            }

            if (numCuenta === "" || !/^\d{10,20}$/.test(numCuenta)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor, ingrese un número de cuenta válido (10-20 dígitos).'
                });
                return false;
            }

            Swal.fire({
                icon: 'success',
                title: 'Pago realizado',
                text: 'Su pago mediante transferencia bancaria ha sido procesado exitosamente.'
            });
            return true;    
        }

        
    </script>
</body>

</html>
