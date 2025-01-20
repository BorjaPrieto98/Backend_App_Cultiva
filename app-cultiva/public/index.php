<?php
require_once __DIR__ . '/../config/db.php';
header("Access-Control-Allow-Origin: *"); // Permitir solicitudes desde tu frontend
header("Access-Control-Allow-Headers: Authorization, Content-Type");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Credentials: true"); // Si necesitas enviar cookies o autenticación


$uri = str_replace('/public', '', $_SERVER['REQUEST_URI']);
$method = $_SERVER['REQUEST_METHOD'];

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

    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            $decoded = json_decode(base64_decode($token), true);
            if (!$decoded || !isset($decoded['id'])) {
                throw new Exception('Token inválido');
            }

            $usuarioId = $decoded['id'];
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Token no válido o mal formado']);
            exit;
        }
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'No autenticado']);
        exit;
    }

    if ($method === 'GET') {
        listarTerrenos($usuarioId);
    } elseif ($method === 'POST') {
        crearTerreno($usuarioId);
    }
} elseif (preg_match('/^\/terrenos\/(\d+)$/', $uri, $matches)) {
    require_once __DIR__ . '/../controllers/terrenos.php';
    $id = $matches[1];

    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader);

        try {
            $decoded = json_decode(base64_decode($token), true);
            if (!$decoded || !isset($decoded['id'])) {
                throw new Exception('Token inválido');
            }

            $usuarioId = $decoded['id'];
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Token no válido o mal formado']);
            exit;
        }
    } else {
        http_response_code(401);
        echo json_encode(['error' => 'No autenticado']);
        exit;
    }

    if ($method === 'PUT') {
        actualizarTerreno($id, $usuarioId);
    } elseif ($method === 'DELETE') {
        eliminarTerreno($id, $usuarioId);
    }
} elseif ($uri === '/usuarios/registro' && $method === 'POST') {
    require_once __DIR__ . '/../controllers/usuarios.php';
    registrarUsuario();
} elseif ($uri === '/usuarios/login' && $method === 'POST') {
    require_once __DIR__ . '/../controllers/usuarios.php';
    iniciarSesion();
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Página no encontrada']);
}
?>
