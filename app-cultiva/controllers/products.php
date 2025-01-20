<?php
require_once __DIR__ . '/../models/product.php';

function listarProductos() {
    global $pdo; // Utiliza la conexión de db.php
    $productoModel = new Producto($pdo);
    $productos = $productoModel->listar();

    header('Content-Type: application/json');
    echo json_encode($productos);
}

function crearProducto() {
    global $pdo; // Utiliza la conexión de db.php
    $data = json_decode(file_get_contents('php://input'), true);
    if(!isset($data['nombre'], $data['categoria'], $data['rentabilidad'])) {
        http_response_code(400);
        echo json_encode(["error" => "Petición incorrecta"]);
        return;
    }
    $productoModel = new Producto($pdo);
    $id = $productoModel->crear($data['nombre'], $data['categoria'], $data['rentabilidad']);
    http_response_code(201); // Fix typo here
    echo json_encode(["id" => $id]);
}

function actualizarProducto($id) {
    global $pdo;
    $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($data['nombre'], $data['categoria'], $data['rentabilidad'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Datos incompletos']);
        return;
    }

    $productoModel = new Producto($pdo);
    $resultado = $productoModel->actualizar($id, $data['nombre'], $data['categoria'], $data['rentabilidad']);

    if ($resultado) {
        echo json_encode(['message' => 'Producto actualizado']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Producto no encontrado']);
    }
}

function eliminarProducto($id) {
    global $pdo;
    $productoModel = new Producto($pdo);
    $resultado = $productoModel->eliminar($id);

    if ($resultado) {
        echo json_encode(['message' => 'Producto eliminado']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Producto no encontrado']);
    }
}

?>
