<?php
require_once __DIR__ . '/../config/db.php';

$uri = str_replace('/public', '', $_SERVER['REQUEST_URI']);
$method = $_SERVER['REQUEST_METHOD'];

// Ruta base
if ($uri === '/' || $uri === '/productos') {
    require_once __DIR__ . '/../controllers/products.php';
    
    if ($method === 'GET') {
        listarProductos();
    } elseif ($method === 'POST') {
        crearProducto();
    }
} elseif (preg_match('/^\/productos\/(\d+)$/', $uri, $matches)) {
    require_once __DIR__ . '/../controllers/products.php';
    $id = $matches[1];

    if ($method === 'PUT') {
        actualizarProducto($id);
    } elseif ($method === 'DELETE') {
        eliminarProducto($id);
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Método no permitido']);
    }
} elseif ($uri === '/terrenos') {
    require_once __DIR__ . '/../controllers/terrenos.php';

    if ($method === 'GET') {
        listarTerrenos($_GET['usuario_id']); // Recibe usuario_id como parámetro
    } elseif ($method === 'POST') {
        crearTerreno();
    }
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Página no encontrada']);
}
?>
