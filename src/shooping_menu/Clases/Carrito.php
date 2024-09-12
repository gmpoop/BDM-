<?php
class Carrito {
    public $id;
    public $nombre;
    public $categoria;
    public $nivel;
    public $precio;
    public $id_user;

    public function __construct($id, $nombre, $categoria, $nivel, $precio, $id_user) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->categoria = $categoria;
        $this->nivel = $nivel;
        $this->precio = $precio;
        $this->id_user = $id_user;
    }

    // Puedes añadir métodos adicionales aquí
}
?>
