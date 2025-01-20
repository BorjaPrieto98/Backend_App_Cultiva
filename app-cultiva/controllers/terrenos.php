<?php
require_once __DIR__ . '/../models/terreno.php';

function listarTerrenos($usuarioId) {
    global $pdo;
    $terrenoModel = new Terreno($pdo);
    $terrenos = $terrenoModel->listarPorUsuario($usuarioId);

    header('Content-Type: application/json');
    echo json_encode($terrenos);
}

function crearTerreno() {
    global $pdo;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['usuario_id'], $data['ubicacion'], $data['tamaño'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Petición incorrecta']);
        return;
    }

    $terrenoModel = new Terreno($pdo);
    $id = $terrenoModel->crear($data['usuario_id'], $data['ubicacion'], $data['tamaño']);

    http_response_code(201);
    echo json_encode(['id' => $id]);
}
?>
