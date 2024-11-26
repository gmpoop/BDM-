<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title class=""></title>
    <link href="../styles/output.css" rel="stylesheet">
</head>
<body class=""> 

<header class="bg-white shadow-md fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">

            <div class="flex-shrink-0 flex items-center">
                <span class="ml-2 text-2xl font-bold text-[#4821ea]">iCraft</span>
            </div>

        </div>
    </div>

</header>



<main class="pt-20 bg-purple-50 w-auto ">
    <section class="mx-auto p-6 bg-white shadow-lg rounded-lg ">
        <!-- Contenido aquí -->

        <upper class="flex flex-wrap justify-between lg:flex-nowrap gap-5 w-full" >
            <div class="flex justify-center mb-6  md:max-w-[50%] h-auto">
                <img src="https://via.placeholder.com/600x400" alt="Cursos iCraft" class="rounded-lg shadow-lg">
            </div>
            
             <div class="md:max-w-[40%] space-y-5 pt-20">
                 <h1 class="text-5xl font-bold md:text-end mb-4 text-[#000000]">Bienvenido a <span class = "text-[#4821ea]">iCraft </span></h1>
                 <p class="md:text-end mb-6 text-[#4821ea]">Explora nuestros cursos y aprende nuevas habilidades.</p>
                 <p class="mb-6 md:text-end text-gray-700">Lorem ipsum dolor sit amet consectetur adipisicing elit. Pariatur, beatae saepe deleniti obcaecati vero, vel quam asperiores nesciunt placeat veniam nam ducimus atque ratione reprehenderit quia et laboriosam numquam ut?</p>
                 <div class="flex md:justify-end gap-5 items-center pt-6">
                    <p class="text-[#4821ea]">
                       <a class="hover:text-purple-700 transition duration-300" href="">Iniciar sesión
                        </a>                     
                    </p>
                    <button class=" bg-[#4821ea] text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition duration-300">
                        Registrarse
                    </button>
                    
                 </div>
             </div>
        </upper>

        <div class="flex justify-end m-5 ">
            <h2 class="text-3xl md:text-4xl  font-bold text-gray-800"> <span class="text-[#4821ea]">iCraft</span></h2>                
        </div>

        <lower class="pt-20 px-4 ">

            <div class="flex justify-center m-5">
                    <h2 class="text-3xl md:text-4xl  font-bold text-gray-800">Conoce más sobre <span class="text-[#4821ea]">nosotros</span></h2>                
            </div>
            <div class="max-w-7xl mx-auto grid gap-8 items-center">
              <!-- Contenedor del video -->
              <div class="relative">
                  <!-- Imagen de fondo o portada del video -->
                   <div class="flex justify-center w-full">
                    <div class="rounded-lg shadow-lg overflow-hidden ">
                        <img src="https://via.placeholder.com/600x400" alt="Video de la empresa" class="max-w-[450px] w-full h-full object-cover">
                    </div>
                   </div>
                  <!-- Botón de reproducción sobre el video -->
                  <button class="absolute inset-0 flex items-center justify-center">
                    <svg class="w-16 h-16 text-white bg-[#4821ea] rounded-full p-3 cursor-pointer" fill="currentColor" viewBox="0 0 24 24">
                      <path d="M8 5v14l11-7z"></path>
                    </svg>
                  </button>
              </div>
              
              <!-- Contenedor del texto -->
              <div class="flex flex-col justify-center">
                
                <div class="text-3xl md:text-4xl text-center font-bold text-gray-800">
                    <p class="">
                      ¡Dale <span class="text-[#4821ea]">play</span> al video para 
                    </p>
                    <p class="">
                      saber más sobre nuestra misión y visión!
                    </p>
                </div>
      
                <!-- Botón de llamada a la acción -->
                 <div class="flex justify-center">
                    <!-- <button class="w-auto mt-6 bg-[#4821ea] text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition duration-300">
                        Ver más videos
                      </button> -->
                 </div>
              </div>
            </div>
        </lower>
         
    </section>
    

    <carousel-section class="w-auto min-h-[1200px] py-20 px-20">
        <div class="m-5">
            <div class=" ">
              <div class="flex items-center gap-3">
                <h1 class="title-section-courses  justify-start py-7">
                  Conoce cada uno de nuestra cursos...
                </h1>
              </div>
              <div class="w-auto flex flex-col items-center">
                <div class="flex justify-center items-center m-5 gap-7">

                  <!-- Aqui eran los Cursos -->
                   <div class="w-auto flex justify-center bg-white rounded">
                     <div class="min-w-[300px] max-w-sm bg-white rounded-xl shadow-md p-6 flex flex-col items-start">
                       <img src="https://via.placeholder.com/300x200" alt="Curso 1" class="rounded-lg mb-4">
                       <h3 class="text-lg font-bold text-gray-900 mb-2">Curso de Desarrollo Web</h3>
                       <p class="text-gray-600 mb-4">Aprende las bases del desarrollo web con HTML, CSS y JavaScript.</p>
                       <a href="#" class=" bg-[#4821ea] text-white py-2 px-4 rounded-lg hover:bg-[#3415b8]">Ver más</a>
                     </div>
                     <div class="flex items-center gap-3">    
                      <div class="p-7 flex flex-col items-center gap-5">
                        <p class="title-section-courses  justify-end">Posiblemente aquí manejamos un descripción mas detallada de los cursos o un video</p>
                        <img src="https://via.placeholder.com/300x200" alt="Curso 1" class="rounded-lg mb-4 min-w-[300PX]">
                      </div>
                    </div>
                   </div>
                </div>

                <div class="flex gap-2">
                  <button class=" bg-white border border-gray-300 rounded-full size-3 shadow-md hover:bg-[#4821ea] hover:text-white z-10" id="">
                    <i class="fas fa-chevron-right"></i>
                  </button>
                  <button class=" bg-white border border-gray-300 rounded-full size-3 shadow-md hover:bg-[#4821ea] hover:text-white z-10" id="">
                    <i class="fas fa-chevron-right"></i>
                  </button>
                  <button class=" bg-white border border-gray-300 rounded-full size-3 shadow-md hover:bg-[#4821ea] hover:text-white z-10" id="">
                    <i class="fas fa-chevron-right"></i>
                  </button>

                </div>
                
              </div>

              <div class="flex items-center gap-3">
                <p class="title-section-courses  justify-start py-7">¡Adquiere tu subscripción y recibe premios!</p>
              </div>
            </div>

            <div class="flex justify-center items-center gap-5 ">
              <!-- Botón Izquierdo -->
                    <button class="transform -translate-y-1/2 bg-white border border-gray-300 rounded-full p-2 shadow-md hover:bg-[#4821ea] hover:text-white z-10" id="prevBtn">
                      <i class="fas fa-chevron-left"></i>
                    </button>
              
              <!-- Contenedor del Carousel -->
              <div class="overflow-hidden ">
                <div class=" flex justify-center space-x-6 transition-transform duration-300 ease-in-out" id="carousel">
                  <!-- Carta 1 -->
                  
          
                  <!-- Carta 2 -->
                  <div class="min-w-[300px] max-w-sm bg-white rounded-xl shadow-md p-6 flex flex-col items-start">
                    <img src="https://via.placeholder.com/300x200" alt="Curso 2" class="rounded-lg mb-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Curso de Diseño UX/UI</h3>
                    <p class="text-gray-600 mb-4">Diseña experiencias de usuario atractivas con las mejores prácticas de UX/UI.</p>
                    <a href="#" class="mt-auto bg-[#4821ea] text-white py-2 px-4 rounded-lg hover:bg-[#3415b8]">Ver más</a>
                  </div>
          
                  <!-- Carta 3 -->
                  <div class="min-w-[300px] max-w-sm bg-white rounded-xl shadow-md p-6 flex flex-col items-start">
                    <img src="https://via.placeholder.com/300x200" alt="Curso 3" class="rounded-lg mb-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Curso de Data Science</h3>
                    <p class="text-gray-600 mb-4">Conviértete en un científico de datos y aprende a analizar y visualizar datos.</p>
                    <a href="#" class="mt-auto bg-[#4821ea] text-white py-2 px-4 rounded-lg hover:bg-[#3415b8]">Ver más</a>
                  </div>
          
                  <!-- Carta 4 -->
                  <!-- <div class="min-w-[300px] max-w-sm bg-white rounded-xl shadow-md p-6 flex flex-col items-start">
                    <img src="https://via.placeholder.com/300x200" alt="Curso 4" class="rounded-lg mb-4">
                    <h3 class="text-lg font-bold text-gray-900 mb-2">Curso de Marketing Digital</h3>
                    <p class="text-gray-600 mb-4">Domina las estrategias de marketing digital y promoción en redes sociales.</p>
                    <a href="#" class="mt-auto bg-[#4821ea] text-white py-2 px-4 rounded-lg hover:bg-[#3415b8]">Ver más</a>
                  </div> -->
                </div>
              </div>
          
              <!-- Botón Derecho -->
              <button class=" transform -translate-y-1/2 bg-white border border-gray-300 rounded-full p-2 shadow-md hover:bg-[#4821ea] hover:text-white z-10" id="nextBtn">
                <i class="fas fa-chevron-right"></i>
              </button>
            </div>
            
          </div> 


    </carousel-section>

    
    <footer class="bg-white py-20 px-20">
        <div class="max-w-7xl mx-auto grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8">
          <!-- Columna de Compañía -->
          <div>
            <h3 class="text-lg font-bold text-gray-900">Compañía</h3>
            <ul class="mt-4 space-y-2">
              <li><a href="#" class="text-gray-600 hover:text-[#4821ea]">Sobre iCraft</a></li>
              <li><a href="#" class="text-gray-600 hover:text-[#4821ea]">Sign up</a></li>
            </ul>
          </div>
      
          <!-- Columna de Productos -->
          <div>
            <h3 class="text-lg font-bold text-gray-900">Productos</h3>
            <ul class="mt-4 space-y-2">
              <li><a href="#" class="text-gray-600 hover:text-[#4821ea]">Desarrollo</a></li>
              <li><a href="#" class="text-gray-600 hover:text-[#4821ea]">Clima</a></li>
              <li><a href="#" class="text-gray-600 hover:text-[#4821ea]">Desempeño</a></li>
            </ul>
          </div>
      
          <!-- Columna de Soluciones -->

      
          <!-- Columna de Recursos -->
          <div>
            <h3 class="text-lg font-bold text-gray-900">Recursos</h3>
            <ul class="mt-4 space-y-2">
              <li><a href="#" class="text-gray-600 hover:text-[#4821ea]">Descargables</a></li>
              <li><a href="#" class="text-gray-600 hover:text-[#4821ea]">Cursos</a></li>
            </ul>
          </div>
      
          <!-- Columna de Enlaces de Ayuda -->
          <div>
            <h3 class="text-lg font-bold text-gray-900">Enlaces de ayuda</h3>
            <ul class="mt-4 space-y-2">
              <li><a href="#" class="text-gray-600 hover:text-[#4821ea]">Políticas de privacidad</a></li>
              <li><a href="#" class="text-gray-600 hover:text-[#4821ea]">Términos y condiciones</a></li>
            </ul>
          </div>
      
          <!-- Columna de Descarga de App -->
          <div>
            <h3 class="text-lg font-bold text-gray-900">Descarga nuestra app</h3>
            <div class="mt-4 flex flex-col space-y-2">
              <a href="#" class="flex items-center justify-center bg-gray-800 text-white py-2 px-4 rounded-lg hover:bg-gray-900">
                <img src="https://img.icons8.com/color/24/google-play.png" class="mr-2"> Google Play
              </a>
              <a href="#" class="flex items-center justify-center bg-gray-800 text-white py-2 px-4 rounded-lg hover:bg-gray-900">
                <img src="https://img.icons8.com/ios-filled/24/ffffff/mac-os.png" class="mr-2"> App Store
              </a>
            </div>
          </div>
        </div>
      
        <!-- Copyright y Redes Sociales -->
        <div class="mt-10 border-t pt-6 flex flex-col md:flex-row justify-between items-center text-gray-600">
          <p class="text-center">&copy; iCraft 2024 - Todos los derechos reservados</p>
          <div class="flex space-x-4 mt-4 md:mt-0">
            <a href="#" class="text-gray-600 hover:text-[#4821ea]"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-gray-600 hover:text-[#4821ea]"><i class="fab fa-linkedin-in"></i></a>
            <a href="#" class="text-gray-600 hover:text-[#4821ea]"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-gray-600 hover:text-[#4821ea]"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-gray-600 hover:text-[#4821ea]"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
      </footer>

</main>

</body>
</html>



<!-- <div class="flex w-full h-auto">

        <div class="bg-[#f6f7fa] flex w-auto h-auto rounded-lg "

            <div class="flex justify-center w-full h-auto gap-5">
                <div class="w-full ">
                    <?php include 'card.php'; ?>
                </div>
            </div>
        </div>

 </div> -->
    


<script>
const carousel = document.getElementById("carousel");
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");

let currentIndex = 0;
const cardWidth = 350; // Ancho de cada tarjeta incluyendo el margen
const totalCards = carousel.children.length;

// Función para actualizar el desplazamiento del carrusel
function updateCarousel(direction) {
  carousel.style.transition = "transform 0.3s ease-in-out";

  if (direction === "next") {
    if (currentIndex === totalCards - 1) {
      // Si estamos en el último, salta al primero
      currentIndex = 0;
      carousel.style.transition = "none"; // Elimina la transición para un salto instantáneo
      carousel.style.transform = `translateX(0)`;
    } else {
      currentIndex++;
      carousel.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
    }
  } else if (direction === "prev") {
    if (currentIndex === 0) {
      // Si estamos en el primero, salta al último
      currentIndex = totalCards - 1;
      carousel.style.transition = "none"; // Elimina la transición para un salto instantáneo
      carousel.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
    } else {
      currentIndex--;
      carousel.style.transform = `translateX(-${currentIndex * cardWidth}px)`;
    }
  }

  // Reaplica la transición después de un salto
  setTimeout(() => {
    carousel.style.transition = "transform 0.3s ease-in-out";
  }, 20); // Timeout pequeño para asegurar que el DOM se actualice sin transición
}

// Evento para mover el carrusel a la izquierda
prevBtn.addEventListener("click", () => {
  updateCarousel("prev");
});

// Evento para mover el carrusel a la derecha
nextBtn.addEventListener("click", () => {
  updateCarousel("next");
});



</script> 

<style>

.title-section-courses {
  display: flex; /* Usamos flexbox para poder aplicar gap */
  gap: 20px; /* Equivalente a space-y-5 (5 * 4px base) */
  font-size: 1.8rem; /* Equivalente a text-3xl */
  font-weight: bold; /* Equivalente a font-bold */
  color: #1f2937; 
}

#carousel {
  display: flex;
  transition: transform 0.3s ease-in-out;
  will-change: transform;
  overflow: hidden; /* Ocultar los elementos que están fuera del contenedor */
}

.card {
  min-width: 300px; /* Ancho mínimo de cada tarjeta */
  margin-right: 6px; /* Espacio entre tarjetas */
  background-color: #fff; /* Fondo de la tarjeta */
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 16px;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
}


</style>

</html>
