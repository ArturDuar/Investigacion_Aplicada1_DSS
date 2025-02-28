<?php

require_once __DIR__ . '/../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

const SECRET_KEY = 'mi_clave_super_secreta';

function createJwt($payload) {
    $payload['exp'] = time() + (60 * 60); // 1 hora de validez
    return JWT::encode($payload, SECRET_KEY, 'HS256');
}

function validateJwt($token) {
    try {
        return (array) JWT::decode($token, new Key(SECRET_KEY, 'HS256'));
    } catch (Exception $e) {
        throw new Exception('Token inválido', 401);
    }
}