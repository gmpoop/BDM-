<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
;

class cursosControlador
{
    private $db;
    private $curso;

    public function __construct($db)
    {
        $this->db = $db;
        $this->curso = new Curso($db);
    }

    public function create()
{
    // Verificar que el contenido de la solicitud no esté vacío
        // Decodificar los datos JSON del curso
        // $courseData = json_decode(file_get_contents("php://input"));

        // Asegurarse de que todos los campos requeridos no estén vacíos

            if (!isset($_POST['titulo'])) {
                echo "Error: Clave 'titulo' no está definida en \$_POST.";
                exit;
            }

            // Establecer valores de las propiedades del curso
            $this->curso->titulo =  $_POST['titulo'];
            $this->curso->descripcion = $_POST['descripcion'];
            $this->curso->categoria_id = $_POST['categoria_id'];
            $this->curso->instructor_id = $_POST['instructor_id'];
            $this->curso->costo = $_POST['costo'];


            // Manejar la imagen del curso
             // Manejar la imagen del curso como BLOB
             if (isset($_FILES['imagenCurso']) && $_FILES['imagenCurso']['error'] == 0) {
                $imageBlob = file_get_contents($_FILES['imagenCurso']['tmp_name']);
                $this->curso->imagen = $imageBlob;
            } else {
                $this->curso->imagen = null;
            }

            // Crear el curso
            if ($this->curso->create()) { // Asegúrate de tener un método createCourse que maneje la inserción en la base de datos
                $recentCourseId = $this->curso->getOneBy($this->curso->titulo, $this->curso->instructor_id);
                if ($recentCourseId) {
                    http_response_code(201);
                    echo json_encode(array("message" => "Curso creado con éxito.", "curso_id" => $recentCourseId->id));
                }
                else
                {
                    http_response_code(503);
                    echo json_encode(array("message" => "Error obteniendo el curso recién creado."));
                }
                
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "No se pudo crear el curso."));
            }
    
}

    public function getCurso($id)
    {
        $this->curso->id = $id;
        if ($this->curso->getOne($id)) {
            $user_arr = array(
                "id" => $this->curso->id,
                "titulo" => $this->curso->titulo,
                "descripcion" => $this->curso->descripcion,
                "imagen" => $this->curso->imagen,
                "costo" => $this->curso->costo,
                "estado" => $this->curso->estado,
                "categoria_id" => $this->curso->categoria_id,
                "instructor_id" => $this->curso->instructor_id
            );
            http_response_code(200);
            echo json_encode($user_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Usuario no encontrado."));
        }
    }


    public function getAllCursos()
    {
        $stmt = $this->curso->getAll();
        $num = $stmt->num_rows;

        if ($num > 0) {
            $users_arr = array();
            $users_arr["records"] = array();

            while ($row = $stmt->fetch_assoc()) {
                extract($row);
                $user_item = array(
                    "id" => $id,
                    "nombre_completo" => $titulo,
                    "email" => $descripcion,
                    "genero" => $imagen,
                    "fecha_nacimiento" => $ruta_imagen,
                    "ruta_foto" => $costo,
                    "fecha_registro" => $estado,
                    "estado" => $categoria_id,
                    "rol_id" => $instructor_id 
                );
                array_push($users_arr["records"], $user_item);
            }

            http_response_code(200);
            echo json_encode($users_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No se encontraron usuarios."));
        }
    }
    
}