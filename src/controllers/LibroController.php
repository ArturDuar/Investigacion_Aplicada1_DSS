<?php
require_once './models/Libro.php';
require_once './middleware/JwtMiddleware.php';

class LibroController {
    private $db;
    private $libro;

    public function __construct($db) {
        $this->db = $db;
        $this->libro = new Libro($db);
    }

    public function getAllLibros() {
        $stmt = $this->libro->getAll();
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($libros);
    }

    public function createLibro() {
        JwtMiddleware::validateToken(); // Proteger creación de libro

        $data = json_decode(file_get_contents("php://input"));
        $this->libro->nombreLibro = $data->nombreLibro;
        $this->libro->autor = $data->autor;
        $this->libro->editorial = $data->editorial;
        $this->libro->edicion = $data->edicion;

        if ($this->libro->create()) {
            echo json_encode(["mensaje" => "Libro creado"]);
        } else {
            echo json_encode(["mensaje" => "Error al crear libro"]);
        }
    }

    public function getLibroById($id) {
        $stmt = $this->libro->getById($id);
        $libro = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($libro ?: ["mensaje" => "Libro no encontrado"]);
    }
    
    public function updateLibro($id) {
        JwtMiddleware::validateToken();
    
        $data = json_decode(file_get_contents("php://input"));
        $this->libro->ID = $id;
        $this->libro->nombreLibro = $data->nombreLibro;
        $this->libro->autor = $data->autor;
        $this->libro->editorial = $data->editorial;
        $this->libro->edicion = $data->edicion;
    
        echo json_encode($this->libro->update() ? ["mensaje" => "Libro actualizado"] : ["mensaje" => "Error al actualizar"]);
    }
    
    public function deleteLibro($id) {
        JwtMiddleware::validateToken();
    
        echo json_encode($this->libro->delete($id) ? ["mensaje" => "Libro eliminado"] : ["mensaje" => "Error al eliminar"]);
    }
}