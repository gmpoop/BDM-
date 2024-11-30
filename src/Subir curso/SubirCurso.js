

// Obtén el token JWT del local storage
const jwtToken = localStorage.getItem('jwtToken');

const PORT = "http://localhost/BDM-/Backend/API/";

let RecentCourse = null;

const verifyToken = async () => {
    try {
        const response = await fetch(PORT + "api.php/verfiyToken", {
            headers: {
                'Authorization': `Bearer ${jwtToken}`,
            },
            method: 'GET'
        });

        response.json().then(data => {
            console.log("Token verificado", data);
            if (data.message === "Token resuelto") {
                decodedToken = data.data.data.id;
                console.log("ID del usuario", decodedToken);
            }
        }).catch(error => {

        });
    }
    catch {

    }
};

const CreateFolder = async () => {
    const courseTitle = $('#titulo').val().replace(/\s+/g, '_'); // Reemplazar espacios por guiones bajos
    console.log("Título del curso", courseTitle);
    const courseFolder = `C:/xampp/htdocs/BDM-/src/resources/videos/${courseTitle}`;

    // Crear la carpeta en el servidor
    await fetch(PORT + "Cursos/create_folder.php", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ folderPath: courseFolder })
    });

    // Subir los videos a la carpeta creada
    for (let i = 1; i <= $('#niveles').val(); i++) {
        console.log("Subiendo video", i);
        const videoFile = $(`#video${i}`)[0].files[0];
        const videoFormData = new FormData();

        console.log("Video a subir", videoFile);

        videoFormData.append('video', videoFile);
        videoFormData.append('folderPath', courseFolder);

        console.log("FormData", videoFormData);
        await fetch(PORT + "Cursos/upload_video.php", {
            method: 'POST',
            body: videoFormData
        }).then(response => response.json())

    }
};

const CreateCourse = async (formData) => {
    console.log("Creando curso...", formData);
    const response = await fetch(PORT + "Cursos/APICursos.php/cursos", {
        headers: {
            'Authorization': `Bearer ${jwtToken}`,
        },
        method: 'POST',
        body: formData
    });


    await CreateFolder();
    const data = await response.json();

    if (data) {
        return data.curso_id;

    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al crear el curso.',
        });
    }

};

const CreateNivels = async (formData) => {

    console.log("Creando niveles...", formData);
    const CreateNivelResponse = await fetch(PORT + "ApiNiveles.php/nivel", {
        headers: {
            'Authorization': `Bearer ${jwtToken}`,
        },
        method: 'POST',
        body: formData
    });

    const data = await CreateNivelResponse.json();

    if (data) {
        return true;
    } else {
        return false;
    }

};

GetCategories = async () => {

    const CreateCategorieResponse = await fetch(PORT + "APIcategorias.php/categorias", {
        headers: {
            'Authorization': `Bearer ${jwtToken}`,
        },
        method: 'GET'
    });

        const data = await CreateCategorieResponse.json();
        console.log("Categorias data", data);

    if (data) {
        return data;
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Hubo un problema al conseguir las categorias.',
        });
    }



}

$(document).ready(function () {

    const InitComboCategorias = async () => {
        const categoriaSelect = document.getElementById('categoria');
    
        const Categories =  await GetCategories();
        
        console.log("Categorias", Categories);  
    
        // Crear las nuevas opciones
        Categories.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.nombre;
            categoriaSelect.appendChild(option);
        });
    }   

    InitComboCategorias();


    document.getElementById('categoria').addEventListener('click', async function () {
        // Aquí va tu lógica para manejar el evento de cambio
        try {   

            
        } catch (error) {
            console.error('Error manejando el cambio de categoría:', error);
        }
    });
    

    verifyToken();
    const arrCategories = GetCategories()
    console.log("Categorias", arrCategories);
    let currentStep = 1;

    $('#nextStep').click(function () {

        if (!$('#titulo').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El título del curso es obligatorio.',
            });
            return;
        }

        if (!$('#descripcion').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La descripción del curso es obligatoria.',
            });
            return;
        }

        if (!$('#categoria').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debes seleccionar una categoría.',
            });
            return;
        }

        if (!$('#imagenCurso').val()) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debes subir una imagen para el curso.',
            });
            return;
        }

        const niveles = $('#niveles').val();
        if (niveles <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El número de niveles debe ser mayor a 0.',
            });
            return;
        }

        const costo = $('#costo').val();
        if (costo < 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El costo del curso no puede ser negativo.',
            });
            return;
        }



        // Si todo está bien, cambiar de paso
        $('#step1').addClass('d-none');
        $('#step2').removeClass('d-none');
        generateLevelInputs(niveles);
    });

    $('#prevStep').click(function () {
        $('#step2').addClass('d-none');
        $('#step1').removeClass('d-none');
    });


    function generateLevelInputs(levels) {
        let contentUploads = $('#contentUploads');
        contentUploads.empty();

        for (let i = 1; i <= levels; i++) {
            contentUploads.append(`
                <div class="container mt-4">
    <h5>Contenido para el Nivel ${i}</h5>
    <div class="form-group">
        <label for="titulo_video${i}" class="form-label">Titulo del nivel ${i}:</label>
        <input type="text" class="form-control" id="titulo_video${i}" name="titulo_video${i}" placeholder="Ingrese el título del nivel" required>
    </div>
    <div class="form-group">
        <label for="contenido_video${i}" class="form-label">Contenido del Nivel ${i}:</label>
        <input type="text" class="form-control" id="contenido_video${i}" name="contenido_video${i}" placeholder="Ingrese el contenido del nivel" required>
    </div>
    <div class="form-group">
        <label for="video${i}" class="form-label">Subir Video del Nivel ${i}:</label>
        <input type="file" class="form-control" id="video${i}" name="video${i}" accept="video/*" required>
    </div>
</div>

            `);
        }
    }


    $('#createCourse').click(function () {


        let allVideosUploaded = true;
        $('#contentUploads input[type="file"]').each(function () {
            if (!$(this).val()) {
                allVideosUploaded = false;
            }
            else {
                allVideosUploaded = true;

            }
        });

        // Crear una carpeta para el curso y subir el video ahí

        if (!allVideosUploaded) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Debes subir los videos para todos los niveles.',
            });
            return;
        }

        const MakeProcess = async () => {   

            const courseForm = document.getElementById('courseForm');
            // Crear FormData con los datos del formulario
            const CourseFormData = new FormData();
    
            try {
                CourseFormData.append('titulo', $('#titulo').val());
                CourseFormData.append('descripcion', $('#descripcion').val());
                CourseFormData.append('categoria_id', $('#categoria').val());
                CourseFormData.append('instructor_id', decodedToken);
                CourseFormData.append('imagen', $('#imagenCurso')[0].files[0]);
                CourseFormData.append('costo', $('#costo').val());
    
            } catch (error) {
                console.log("Error al manejar un elemento del form", error);
            }
    

            // Iterar sobre las entradas de FormData y mostrarlas
            for (let [key, value] of CourseFormData.entries()) {
                console.log(`${key}: ${value}`);
            }
                        // Enviar datos al servidor
            
            const curso_id = await CreateCourse(CourseFormData);

            try {
                for (let i = 1; i <= $('#niveles').val(); i++) {
                    const NivelFormData = new FormData();
                    const videoFile = $('#video' + i)[0].files[0]; // Obtener el archivo de video
            
                    NivelFormData.append('curso_id', curso_id);
                    NivelFormData.append('titulo', $('#titulo_video' + i).val());
                    NivelFormData.append('contenido', $('#contenido_video' + i).val());
                    if (videoFile) {
    
                        const RuouteComplete = `../resources/videos/${$('#titulo').val().replace(/\s+/g, '_')}/${videoFile.name}`;
    
                        NivelFormData.append('video', RuouteComplete); // Solo el nombre del archivo
                    } else {
                        NivelFormData.append('video', ''); // Manejar el caso en que no haya archivo seleccionado
                    }
            
                    const Valid = await CreateNivels(NivelFormData);

                    if (Valid) {
                      continue;
                    }
                    else{
                        throw new Error("Error al crear los niveles");
                    }
                }

                Swal.fire({
                    icon: 'success',
                    title: '¡Curso creado!',
                    text: 'El curso se ha creado exitosamente.',
                });

            } catch (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error,
                });
            }
        }

        MakeProcess();


    });
});