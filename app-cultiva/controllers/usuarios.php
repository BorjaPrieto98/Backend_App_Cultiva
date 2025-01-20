<?php
require_once __DIR__ . '/../models/usuario.php';

function registrarUsuario() {
    global $pdo;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nombre'], $data['email'], $data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Faltan datos requeridos']);
        return;
    }

    $usuarioModel = new Usuario($pdo);
    if ($usuarioModel->buscarPorEmail($data['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'El email ya está registrado']);
        return;
    }

    $resultado = $usuarioModel->registrar($data['nombre'], $data['email'], $data['password']);
    if ($resultado) {
        http_response_code(201);
        echo json_encode(['message' => 'Usuario registrado con éxito']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al registrar el usuario']);
    }
}

function iniciarSesion() {
    global $pdo;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['email'], $data['password'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Faltan datos requeridos']);
        return;
    }

    $usuarioModel = new Usuario($pdo);
    $usuario = $usuarioModel->buscarPorEmail($data['email']);

    if (!$usuario || !password_verify($data['password'], $usuario['password'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Credenciales inválidas']);
        return;
    }

    // Generar un token simple (JWT es más seguro para producción)
    $token = base64_encode(json_encode(['id' => $usuario['id'], 'email' => $usuario['email']]));
    echo json_encode(['token' => $token]);
}
?>
