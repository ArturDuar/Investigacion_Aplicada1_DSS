<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

require_once "./connection/Connection.php";
require_once './controllers/UsuarioController.php';
require_once './controllers/LibroController.php';

// Conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Obtener la URL solicitada
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', trim($uri, '/'));

// Si detecta api y index.php, se eliminan
if ($uri[0] === 'api') {
    array_shift($uri); // quita "api"
}
if ($uri[0] === 'index.php') {
    array_shift($uri); // quita "index.php"
}

// Método HTTP usado
$method = $_SERVER['REQUEST_METHOD'];

// Controladores
$usuarioController = new UsuarioController($db);
$libroController = new LibroController($db);

// Routing
switch ($uri[0]) {
    case 'usuarios':
        switch ($method) {
            case 'GET':
                if (isset($uri[1])) {
                    $usuarioController->getUsuarioById($uri[1]);
                } else {
                    $usuarioController->getAllUsuarios();
                }
                break;

            case 'POST':
                $usuarioController->createUsuario();
                break;

            case 'PUT':
                if (isset($uri[1])) {
                    $usuarioController->updateUsuario($uri[1]);
                } else {
                    echo json_encode(["mensaje" => "ID de usuario requerido"]);
                }
                break;

            case 'DELETE':
                if (isset($uri[1])) {
                    $usuarioController->deleteUsuario($uri[1]);
                } else {
                    echo json_encode(["mensaje" => "ID de usuario requerido"]);
                }
                break;

            default:
                echo json_encode(["mensaje" => "Método no permitido"]);
                break;
        }
        break;

    case 'libros':
        switch ($method) {
            case 'GET':
                if (isset($uri[1])) {
                    $libroController->getLibroById($uri[1]);
                } else {
                    $libroController->getAllLibros();
                }
                break;

            case 'POST':
                $libroController->createLibro();
                break;

            case 'PUT':
                if (isset($uri[1])) {
                    $libroController->updateLibro($uri[1]);
                } else {
                    echo json_encode(["mensaje" => "ID de libro requerido"]);
                }
                break;

            case 'DELETE':
                if (isset($uri[1])) {
                    $libroController->deleteLibro($uri[1]);
                } else {
                    echo json_encode(["mensaje" => "ID de libro requerido"]);
                }
                break;

            default:
                echo json_encode(["mensaje" => "Método no permitido"]);
                break;
        }
        break;

    case 'login':
        if ($method === 'POST') {
            $usuarioController->login();
        } else {
            echo json_encode(["mensaje" => "Método no permitido"]);
        }
        break;

    default:
        echo json_encode(["mensaje" => "Ruta no válida"]);
        break;
}

