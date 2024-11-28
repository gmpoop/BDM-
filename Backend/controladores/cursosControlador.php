<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;;

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
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $imageBlob = file_get_contents($_FILES['imagen']['tmp_name']);
            $this->curso->imagen = $imageBlob;
        } else {
            $this->curso->imagen = null;
            echo json_encode(array("message" => "Error en el alacemaniento de la imagen."));
        }

        // Crear el curso
        if ($this->curso->create()) { // Asegúrate de tener un método createCourse que maneje la inserción en la base de datos
            $recentCourseId = $this->curso->getOneBy($this->curso->titulo, $this->curso->instructor_id);
            if ($recentCourseId) {
                http_response_code(201);
                echo json_encode(array("message" => "Curso creado con éxito.", "curso_id" => $recentCourseId['id']));
            } else {
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

    public function getCursosPorCategoria()
    {
        $stmt = $this->curso->GetAllByCourse();

        if ($stmt) {
            $stmt->store_result(); // Asegúrate de almacenar el resultado para obtener el número de filas
            $num = $stmt->num_rows;

            if ($num > 0) {
                $cursos_arr = array();
                $cursos_arr["records"] = array();

                $stmt->bind_result($categoria_id, $nombre_categoria, $id_curso, $titulo, $descripcion, $imagen, $costo);
                while ($stmt->fetch()) {
                    $imagenBase64 = base64_encode($imagen);

                    // Crear una URL de datos para la imagen
                    $imagenSrc = 'data:image/jpeg;base64,' . $imagenBase64;

                    $curso_item = array(
                        "categoria_id" => $categoria_id,
                        "nombre_categoria" => $nombre_categoria,
                        "id_curso" => $id_curso,
                        "titulo" => $titulo,
                        "descripcion" => $descripcion,
                        "imagen" => $imagenSrc,
                        "costo" => $costo
                    );
                    array_push($cursos_arr["records"], $curso_item);
                }

                http_response_code(200);
                echo json_encode($cursos_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No se encontraron cursos."));
            }
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Error al obtener los cursos."));
        }
    }

    public function getCursosDetalle($idCurso) {
        $stmt = $this->curso->getCoursesDetails($idCurso);
    
        if ($stmt) {
            $stmt->store_result(); // Asegúrate de almacenar el resultado para obtener el número de filas
            $num = $stmt->num_rows;
    
            if ($num > 0) {
                $cursos_arr = array();
                $cursos_arr["records"] = array();
    
                // Enlazar los resultados con las nuevas columnas
                $stmt->bind_result($idCurso, $titulo_curso, $descripcion, $imagen, $idUsuario, $nombre_completo, $email, $idNivel, $titulo_nivel);
                while ($stmt->fetch()) {
                    $imagenBase64 = base64_encode($imagen);
    
                    // Crear una URL de datos para la imagen
                    $imagenSrc = 'data:image/jpeg;base64,' . $imagenBase64;
    
                    $curso_item = array(
                        "idCurso" => $idCurso,
                        "titulo_curso" => $titulo_curso,
                        "descripcion" => $descripcion,
                        "imagen" => $imagenSrc,
                        "idUsuario" => $idUsuario,
                        "nombre_completo" => $nombre_completo,
                        "email" => $email,
                        "idNivel" => $idNivel,
                        "titulo_nivel" => $titulo_nivel
                    );
                    array_push($cursos_arr["records"], $curso_item);
                }
    
                http_response_code(200);
                echo json_encode($cursos_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "No se encontraron cursos."));
            }
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Error al obtener los cursos."));
        }
    }
    
}
