<?php 
    require_once "./connection/Connection.php";

    class Libro {
            private $conn;
            private $table = "libro";
        
            public $ID;
            public $nombreLibro;
            public $autor;
            public $editorial;
            public $edicion;
        
            public function __construct($db) {
                $this->conn = $db;
            }
        
            public function getAll() {
                $query = "SELECT * FROM " . $this->table;
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                return $stmt;
            }
        
            public function create() {
                $query = "INSERT INTO " . $this->table . " (nombreLibro, autor, editorial, edicion) VALUES (?, ?, ?, ?)";
                $stmt = $this->conn->prepare($query);
                return $stmt->execute([$this->nombreLibro, $this->autor, $this->editorial, $this->edicion]);
            }

            public function getById($id) {
                $query = "SELECT * FROM libro WHERE ID = ?";
                $stmt = $this->conn->prepare($query);
                $stmt->execute([$id]);
                return $stmt;
            }
            
            public function update() {
                $query = "UPDATE libro SET nombreLibro = ?, autor = ?, editorial = ?, edicion = ? WHERE ID = ?";
                $stmt = $this->conn->prepare($query);
                return $stmt->execute([$this->nombreLibro, $this->autor, $this->editorial, $this->edicion, $this->ID]);
            }
            
            public function delete($id) {
                $query = "DELETE FROM libro WHERE ID = ?";
                $stmt = $this->conn->prepare($query);
                return $stmt->execute([$id]);
            }
        }
?>