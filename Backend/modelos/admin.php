<?php
class AdminModel {
    private $db; // Conexión a la base de datos
    private $table_name = "usuarios";

    // Propiedades
    public $id;
    public $nombre_completo;
    public $genero;
    public $fecha_nacimiento;
    public $foto;
    public $ruta_foto;
    public $email;
    public $contraseña;
    public $fecha_registro;
    public $ultimo_cambio;
    public $intentos_fallidos;
    public $estado;
    public $rol_id;

    // Constructor para inicializar la conexión a la base de datos
    public function __construct($pdo) {
        $this->db = $pdo;
    }

    /////
    // Método para obtener los datos del administrador
    /////
    public function obtenerAdministrador() {
        $query = "
            SELECT 
                u.id,
                u.nombre_completo,
                u.genero,
                u.fecha_nacimiento,
                u.foto,
                u.ruta_foto,
                u.email,
                u.contraseña,
                u.fecha_registro,
                u.ultimo_cambio,
                u.intentos_fallidos,
                u.estado,
                u.rol_id
            FROM " . $this->table_name . " u
            INNER JOIN roles r ON u.rol_id = r.id
            WHERE r.nombre = 'Administrador' AND u.estado = 'activo'
            LIMIT 1
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data) {
            // Asignar valores a las propiedades
            $this->id = $data['id'];
            $this->nombre_completo = $data['nombre_completo'];
            $this->genero = $data['genero'];
            $this->fecha_nacimiento = $data['fecha_nacimiento'];
            $this->foto = $data['foto'];
            $this->ruta_foto = $data['ruta_foto'] ?? 'https://i.pinimg.com/474x/38/6c/c3/386cc3135db08ffff59778f34f056199.jpg'; // Ruta predeterminada
            $this->email = $data['email'];
            $this->contraseña = $data['contraseña'];
            $this->fecha_registro = $data['fecha_registro'];
            $this->ultimo_cambio = $data['ultimo_cambio'];
            $this->intentos_fallidos = $data['intentos_fallidos'];
            $this->estado = $data['estado'];
            $this->rol_id = $data['rol_id'];

            return $data; // También retorna el arreglo asociativo si se necesita
        }

        return false; // Retorna false si no se encontró un administrador activo
    }

    
}
