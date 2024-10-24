<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil - iCraft</title>
    <link rel="stylesheet" href="../styles/output.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100 w-full">


    <div class="flex justify-center items-center h-screen w-full">
        <div class="bg-white p-8 rounded-lg shadow-lg w-[70%] m-5">
            <h1 class="text-2xl font-bold text-gray-800 mb-6" id="title"></h1>
            <form class=" w-full" id="editUserForm" method="POST" action ="register.php" enctype="multipart/form-data">
                <div class="flex gap-8 ">
                    <div class="w-full">
    
                        <div class="mb-4">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nombre</label>
                            <input type="text" id="nombre" name="name" placeholder="Nombre del usuario"   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4" id="email_section">
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Correo electrónico </label>
                            <input type="email" id="email" name="email" placeholder="email@ejemplo.com"  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="birthdate" class="block text-gray-700 text-sm font-bold mb-2">Fecha de nacimiento</label>
                            <input type="date" id="birthdate" name="birthdate" placeholder="1990-01-01"   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="gender" class="block text-gray-700 text-sm font-bold mb-2">Género</label>
                            <select id="gender" name="gender"   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option placeholder="male" selected>Masculino</option>
                                <option placeholder="female">Femenino</option>
                                <option placeholder="other">Otro</option>
                            </select>
                        </div>
                    </div>
                    <div class="w-full">
    
                        <div class="mb-4">
                            <label for="avatar" class="block text-gray-700 text-sm font-bold mb-2">Cambiar foto de perfil</label>
                            <input type="file" id="avatar" name="avatar" accept="image/*" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Contraseña</label>
                            <input type="password" id="password" name="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Al menos 8 caracteres">
                        </div>
                        <div class="mb-4">
                            <label for="password-confirm" class="block text-gray-700 text-sm font-bold mb-2">Confirmar nueva contraseña</label>
                            <input type="password" id="password-confirm" name="password_confirmation" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
    
                    </div>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <button  id="saveButton" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" >
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>

        const title = document.getElementById("title");
        const email = document.getElementById("email_section");  // aunque está deshabilitado, lo dejo por si lo habilitas en algún momento
        const urlParams = new URLSearchParams(window.location.search);

        const type = urlParams.get('type');

        if(type === "reg") 
            title.textContent = "Registrar";  
        else if (type === "edit") {
            email.classList.add("hidden")
            title.textContent = "Editar Usuario";  
        }

        document.getElementById('editUserForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevenir el envío normal del formulario
    
    if (validarFormulario()) {
        var formData = new FormData(this); // Recoge los datos del formulario

        console.log(formData)
        fetch('register.php', {
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



    function validarFormulario() {

    const name = document.getElementById("nombre").value.trim();
    const email = document.getElementById("email").value.trim();  // aunque está deshabilitado, lo dejo por si lo habilitas en algún momento
    const birthdate = document.getElementById("birthdate").value;
    const gender = document.getElementById("gender").value.trim();
    const avatar = document.getElementById("avatar").files[0];
    const password = document.getElementById("password").value.trim();
    const passwordConfirm = document.getElementById("password-confirm").value.trim();

    let validate  = true; 
    console.log(name.value)

    // Validar nombre
    if (!name ) {
        console.log("si entra")
        validate = false;
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor, ingrese un correo electrónico válido.',
            confirmButtonColor: '#4821ea'
        });
        
    }

    // Validar correo electrónico (aunque esté deshabilitado)
    if(type !== "edit") {
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

    }
  
    // Validar fecha de nacimiento
    if (birthdate === "") {
        validate = false;
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor, ingrese su fecha de nacimiento.',
            confirmButtonColor: '#4821ea'
        });
        return false;
    }

    // Validar género
    if (gender === "") {
        validate = false;
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Por favor, seleccione su género.',
            confirmButtonColor: '#4821ea'
        });
        return false;
    }

    // Validar si se ha seleccionado una foto de perfil
    // if (!avatar) {
    //     validate = false;
    //     Swal.fire({
    //         icon: 'error',
    //         title: 'Error',
    //         text: 'Por favor, seleccione una foto de perfil.',
    //         confirmButtonColor: '#4821ea'
    //     });
    //     return false;
    // }

    // Validar contraseñas opcionales
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

    console.log(password)
    // Verificar si la contraseña tiene al menos una letra mayúscula, un número y un carácter especial
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

    // Confirmar que las contraseñas coincidan
    if (password !== passwordConfirm) {
        validate = false;
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Las contraseñas no coinciden.',
            confirmButtonColor: '#4821ea'
        });
        return false;
    }
}
else 
    {     
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Llenar el campo contraseña',
            confirmButtonColor: '#4821ea'
        });

        return false;
    }

    return true;

}

    </script>
</body>
</html>
