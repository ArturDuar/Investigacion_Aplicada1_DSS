<?php
class Usuario {
    private $conn;

    public $IDusuario;
    public $nombreUsuario;
    public $apellidoUsuario;
    public $contrasennia;
    public $IDrol;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT u.IDusuario, u.nombreUsuario, u.apellidoUsuario, r.nombreRol 
                  FROM usuario u 
                  INNER JOIN rol r ON u.IDrol = r.IDrol";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getById($id) {
        $query = "SELECT u.IDusuario, u.nombreUsuario, u.apellidoUsuario, r.nombreRol 
                  FROM usuario u 
                  INNER JOIN rol r ON u.IDrol = r.IDrol
                  WHERE u.IDusuario = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create() {
        $query = "INSERT INTO usuario (nombreUsuario, apellidoUsuario, contrasennia, IDrol) 
                  VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $this->nombreUsuario,
            $this->apellidoUsuario,
            $this->contrasennia,
            $this->IDrol
        ]);
    }

    public function update() {
        $query = "UPDATE usuario 
                  SET nombreUsuario = ?, apellidoUsuario = ?, 
                      contrasennia = IF(?='', contrasennia, ?), 
                      IDrol = ? 
                  WHERE IDusuario = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $this->nombreUsuario,
            $this->apellidoUsuario,
            $this->contrasennia,
            $this->contrasennia,
            $this->IDrol,
            $this->IDusuario
        ]);
    }

    public function delete($id) {
        $query = "DELETE FROM usuario WHERE IDusuario = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function login($nombreUsuario) {
        $sql = "SELECT * FROM usuario WHERE nombreUsuario = :nombreUsuario";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nombreUsuario', $nombreUsuario);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve el usuario completo (incluye contraseña hash)
    }
}
