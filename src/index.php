<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title class="text-zinc-900	">Dashboard</title>
    <link href="./output.css" rel="stylesheet">
</head>
<body class="h-auto"> 


<header class="bg-white shadow-md fixed w-full z-50">
    <?php include 'navbar.php'; ?>
</header>

<main class="pt-20 bg-purple-50 min-h-screen ">
    <div class="background-main flex max-w-7xl mx-auto p-6 bg-white shadow-lg rounded-lg gap-5">
        <!-- Contenido aquí -->
        <div class="flex justify-center mb-6">
            <img src="https://via.placeholder.com/600x400" alt="Cursos iCraft" class="rounded-lg shadow-lg">
        </div>
        

         <div>
             <h1 class="text-5xl font-bold text-start mb-4 text-[#000000]">Bienvenido a <span class = "text-purple-700">i</span>Craft</h1>
             <p class="text-start mb-6 text-purple-600">Explora nuestros cursos y aprende nuevas habilidades.</p>
             <p class="mb-6 text-gray-700">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nesciunt quaerat numquam repellendus sint quam quas pariatur mollitia cum. Error dolor iste atque incidunt corrupti delectus maxime, quasi beatae consequatur eaque?</p>
             
             <div>
                <button class="bg-purple-700 text-white font-bold py-2 px-4 rounded-full hover:bg-purple-800 focus:outline-none focus:ring-2 focus:ring-purple-600 focus:ring-opacity-50">
                    Ingresar sesión
                </button>

             </div>
         </div>
    </div>
    
    <!-- <div class="flex justify-center mt-10">
        <form class="w-full max-w-lg bg-white p-6 rounded-lg shadow-md" action="">
            <div class="mb-4">
                <h2 class="text-2xl font-bold text-purple-700 mb-2">Ingresar a iCraft</h2>
                <p class="text-purple-600 mb-4">Consigue la mayor puntuación en todos los cursos</p>
            </div>
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Nombre</label>
                <input name="name" type="text" class="w-full border rounded p-2 mb-2">
            </div>
            <div class="mb-4">
                <label for="lastname" class="block text-gray-700">Apellido</label>
                <input name="lastname" type="text" class="w-full border rounded p-2 mb-2">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Correo</label>
                <input name="email" type="email" class="w-full border rounded p-2 mb-2">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Contraseña</label>
                <input name="password" type="password" class="w-full border rounded p-2 mb-2">
            </div>
            <div class="flex justify-end">
                <button type="submit" class="bg-purple-700 text-white font-bold py-2 px-4 rounded hover:bg-purple-800">Ingresar</button>
            </div>
        </form>
    </div> -->
</main>


<div class="flex w-full h-auto">

        <div class="bg-[#f6f7fa] flex w-auto h-auto rounded-lg "

            <div class="flex justify-center w-full h-auto gap-5">
                <div class="w-full ">
                    <?php include 'card.php'; ?>
                </div>
            </div>
        </div>

    </div>
    
    
    <section>
    </section>

</body>

<script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden'); // Mostrar/Ocultar el menú
        });
    </script> 

<style>

    .background-main {
        background-image: 
    }

    .user-svg {
        justify-content: space-between;
    }


    .icon-navbar {
        padding: 10px;
    }

    .section-text {
        margin: 5px;
    }

</style>

</html>
