<?php
require_once './models/Usuario.php';
require_once './middleware/JwtMiddleware.php';

class UsuarioController {
    private $db;
    private $usuario;

    public function __construct($db) {
        $this->db = $db;
        $this->usuario = new Usuario($db);
    }

    // Obtener todos los usuarios (requiere token)
    public function getAllUsuarios() {
        JwtMiddleware::validateToken();

        $stmt = $this->usuario->getAll();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // Obtener un usuario por ID (requiere token)
    public function getUsuarioById($id) {
        JwtMiddleware::validateToken();

        $usuario = $this->usuario->getById($id);
        echo json_encode($usuario ?: ["mensaje" => "Usuario no encontrado"]);
    }

    // Crear un usuario nuevo (requiere token)
    public function createUsuario() {
        JwtMiddleware::validateToken();

        $data = json_decode(file_get_contents("php://input"));
        $this->usuario->nombreUsuario = $data->nombreUsuario;
        $this->usuario->apellidoUsuario = $data->apellidoUsuario;
        $this->usuario->contrasennia = $data->contrasennia;
        $this->usuario->IDrol = $data->IDrol;

        if ($this->usuario->create()) {
            echo json_encode(["mensaje" => "Usuario creado exitosamente"]);
        } else {
            echo json_encode(["mensaje" => "Error al crear el usuario"]);
        }
    }

    // Actualizar un usuario (requiere token)
    public function updateUsuario($id) {
        JwtMiddleware::validateToken();

        $data = json_decode(file_get_contents("php://input"));
        $this->usuario->IDusuario = $id;
        $this->usuario->nombreUsuario = $data->nombreUsuario;
        $this->usuario->apellidoUsuario = $data->apellidoUsuario;
        $this->usuario->IDrol = $data->IDrol;

        // Si envían contraseña, actualizarla
        if (!empty($data->contrasennia)) {
            $this->usuario->contrasennia = $data->contrasennia;
        }

        if ($this->usuario->update()) {
            echo json_encode(["mensaje" => "Usuario actualizado correctamente"]);
        } else {
            echo json_encode(["mensaje" => "Error al actualizar el usuario"]);
        }
    }

    // Eliminar un usuario (requiere token)
    public function deleteUsuario($id) {
        JwtMiddleware::validateToken();

        if ($this->usuario->delete($id)) {
            echo json_encode(["mensaje" => "Usuario eliminado"]);
        } else {
            echo json_encode(["mensaje" => "Error al eliminar el usuario"]);
        }
    }

    // Login - No requiere token (genera el token)
    public function login() {
        $data = json_decode(file_get_contents("php://input"));

        $usuario = $this->usuario->login($data->nombreUsuario);

        if ($usuario && $data->contrasennia === $usuario['contrasennia']) {
            $token = JwtMiddleware::generateToken([
                "IDusuario" => $usuario['IDusuario'],
                "nombreUsuario" => $usuario['nombreUsuario'],
                "IDrol" => $usuario['IDrol']
            ]);
            echo json_encode(["token" => $token]);
        } else {
            echo json_encode(["mensaje" => "Credenciales incorrectas"]);
        }
    }
}
?>
