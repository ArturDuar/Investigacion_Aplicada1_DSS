<?php
require_once './vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtMiddleware {
    private static $secret_key = "MI_CLAVE_SECRETA"; 

    public static function generateToken($userId) {
        $payload = [
            "userId" => $userId,
            "exp" => time() + 3600
        ];
        return JWT::encode($payload, self::$secret_key, 'HS256');
    }

    public static function validateToken() {
        $headers = apache_request_headers();
        if (!isset($headers['Authorization'])) {
            http_response_code(401);
            echo json_encode(["mensaje" => "No autorizado"]);
            exit;
        }

        $token = str_replace("Bearer ", "", $headers['Authorization']);
        try {
            JWT::decode($token, new Key(self::$secret_key, 'HS256'));
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(["mensaje" => "Token inválido"]);
            exit;
        }
    }
}