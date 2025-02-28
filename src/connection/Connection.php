<?php

    class Database {
        private $host = "db";
        private $db_name = "libreria";
        private $username = "root";
        private $password = "123456";
        public $conn;

        public function getConnection() {
            $this->conn = null;
            try {
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                                    $this->username, $this->password);
                $this->conn->exec("set names utf8");
            } catch (PDOException $exception) {
                echo "Error de conexión: " . $exception->getMessage();
            }
            return $this->conn;
        }
    }
    
/*

//configuracion para la img de mysql de docker

<?php
$host     = 'db';           // El servicio de MySQL se llama "db" según docker-compose
$dbname   = 'libreria';     // La base de datos se llama "Libreria"
$username = 'root';         // Usuario, en este ejemplo se usa root
$password = '123456';       // Contraseña establecida en docker-compose

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
*/ 
    
?>
 
