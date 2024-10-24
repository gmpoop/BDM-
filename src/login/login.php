
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="sessionstyles.css">
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
</head>
<body>
   
    <video class="video-background" autoplay muted loop>
        <source src="./7101912-uhd_2560_1440_25fps.mp4" type="video/mp4">
    </video>

   
    <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="login-card text-center">
            <h2>Bienvenido de vuelta</h2>
            <p class="text-muted">Qué bueno verte otra vez</p>
            <form id="loginForm">
                <div class="form-group">
                    <input  class="form-control" id="email" name="email" placeholder="Correo Electrónico" >
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Contraseña" >
                </div>
                <button id="loginForm" onclick="log()" type="submit" class="btn btn-primary btn-block">Iniciar Sesión</button>
            </form>
            <div class="mt-3">
                <a href="#" class="text-muted">¿Olvidaste tu contraseña?</a>
            </div>
            <hr>
            <p class="text-muted">¿Es la primera vez que usas nuestro servicio? <a href="#">Regístrate</a></p>
        </div>
    </div>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Bootstrap JS and dependencies -->
   
    <script>
        
        document.getElementById('editUserForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevenir el envío normal del formulario
    
    if (log()) {
        var formData = new FormData(this); // Recoge los datos del formulario

        console.log(formData)
        fetch('log.php', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Verificamos si el response tiene un status 200 (éxito)
            if (response.ok) {
                return response.json(); // Asumimos que el servidor devuelve un JSON
            } else {
                throw new Error('Correo electronico ya existente');
            }
        })
        .then(data => {
            // Si la respuesta contiene algún dato relevante de éxito (como 'success' en JSON)
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Registro exitoso',
                    text: data.message || 'Los cambios se han guardado exitosamente.',
                });
            } else {
                throw new Error(data.message || 'Error en la respuesta del servidor.');
            }
        })
        .catch(error => {
            // Si hay cualquier error (en la solicitud o en la respuesta del servidor)
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Hubo un error al guardar los cambios.',
            });
        });
    }
});

        function log(){ 
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

            let validate  = true; 

            if (email === "" || !/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/.test(email)) {
                console.log(type)
                validate = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Por favor, ingrese un correo electrónico válido.',
                    confirmButtonColor: '#4821ea'
                });
                return false;
                
            }

            if (password !== "") {
                // Verificar si la contraseña tiene menos de 8 caracteres
                if (password.length < 8) {
                    validate = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La nueva contraseña debe tener al menos 8 caracteres.',
                        confirmButtonColor: '#4821ea'
                    });
                    return false;
                }
                
                // Verificar si la contraseña tiene al menos un número, una mayúscula y un carácter especial
                const regex = /^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

                if (!regex.test(password)) {
                    validate = false;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La contraseña debe contener al menos una letra mayúscula, un número y un carácter especial.',
                        confirmButtonColor: '#4821ea'
                    });
                    return false;
                }
            }

        }

        document.getElementById('loginForm').addEventListener('submit', function(event) {
            event.preventDefault();  

            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;

           
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailPattern.test(email)) {
                console.log("Correo inválido detectado");
                Swal.fire({
                    icon: 'error',
                    title: 'Correo inválido',
                    text: 'Por favor, ingresa un correo electrónico válido.',
                });
                return;  
            }

            
            if (password.length < 8) {
                Swal.fire({
                    icon: 'error',
                    title: 'Contraseña incorrecta',
                    text: 'La contraseña debe tener al menos 8 caracteres.',
                });
                return;
            }

            // Simulación de éxito
            Swal.fire({
                icon: 'success',
                title: 'Inicio de sesión exitoso',
                text: 'Has iniciado sesión correctamente.',
            }).then(() => {
                // Aquí puedes redirigir al usuario a otra página o procesar el login
                // window.location.href = "dashboard.html"; 
            });
        });
    </script>
</body>
</html>
