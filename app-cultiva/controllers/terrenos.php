<?php
require_once __DIR__ . '/../models/terreno.php';

function listarTerrenos($usuarioId) {
    global $pdo;
    $terrenoModel = new Terreno($pdo);
    $terrenos = $terrenoModel->listarPorUsuario($usuarioId);

    header('Content-Type: application/json');
    echo json_encode($terrenos);
}

function crearTerreno($usuarioId) {
    global $pdo;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['ubicacion'], $data['tamaño'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Faltan datos requeridos']);
        return;
    }

    $terrenoModel = new Terreno($pdo);
    $id = $terrenoModel->crear($usuarioId, $data['ubicacion'], $data['tamaño']);

    http_response_code(201);
    echo json_encode(['id' => $id]);
}

function actualizarTerreno($id, $usuarioId) {
    global $pdo;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['ubicacion'], $data['tamaño'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Faltan datos requeridos']);
        return;
    }

    $terrenoModel = new Terreno($pdo);
    $resultado = $terrenoModel->actualizar($id, $usuarioId, $data['ubicacion'], $data['tamaño']);

    if ($resultado) {
        echo json_encode(['message' => 'Terreno actualizado']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Terreno no encontrado']);
    }
}

function eliminarTerreno($id, $usuarioId) {
    global $pdo;
    $terrenoModel = new Terreno($pdo);
    $resultado = $terrenoModel->eliminar($id, $usuarioId);

    if ($resultado) {
        echo json_encode(['message' => 'Terreno eliminado']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Terreno no encontrado']);
    }
}
?>
