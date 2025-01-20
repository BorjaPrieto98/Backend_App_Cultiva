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

    // Obtener el header Authorization de forma segura
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader); // Extraer el token

        // Decodificar el token
        try {
            $decoded = json_decode(base64_decode($token), true); // Decodificar Base64 a JSON
            if (!$decoded || !isset($decoded['id'])) {
                throw new Exception('Token inválido');
            }

            $usuarioId = $decoded['id']; // Usuario autenticado
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

    // Manejar métodos GET y POST para terrenos
    if ($method === 'GET') {
        listarTerrenos($usuarioId); // Listar terrenos para el usuario autenticado
    } elseif ($method === 'POST') {
        crearTerreno($usuarioId); // Crear un terreno asociado al usuario
    }
} elseif (preg_match('/^\/terrenos\/(\d+)$/', $uri, $matches)) {
    require_once __DIR__ . '/../controllers/terrenos.php';
    $id = $matches[1];

    // Obtener el header Authorization de forma segura
    $headers = getallheaders();
    if (isset($headers['Authorization'])) {
        $authHeader = $headers['Authorization'];
        $token = str_replace('Bearer ', '', $authHeader); // Extraer el token

        // Decodificar el token
        try {
            $decoded = json_decode(base64_decode($token), true); // Decodificar Base64 a JSON
            if (!$decoded || !isset($decoded['id'])) {
                throw new Exception('Token inválido');
            }

            $usuarioId = $decoded['id']; // Usuario autenticado
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

    // Manejar métodos PUT y DELETE para terrenos específicos
    if ($method === 'PUT') {
        actualizarTerreno($id, $usuarioId); // Actualizar terreno
    } elseif ($method === 'DELETE') {
        eliminarTerreno($id, $usuarioId); // Eliminar terreno
    }
}elseif ($uri === '/usuarios/registro' && $method === 'POST') {
    require_once __DIR__ . '/../controllers/usuarios.php';
    registrarUsuario();
} elseif ($uri === '/usuarios/login' && $method === 'POST') {
    require_once __DIR__ . '/../controllers/usuarios.php';
    iniciarSesion();
}else {
    http_response_code(404);
    echo json_encode(['error' => 'Página no encontrada']);
}
?>
