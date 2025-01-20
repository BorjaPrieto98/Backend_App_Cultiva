<?php
require_once __DIR__ . '/../config/db.php';

class Terreno {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Listar terrenos de un usuario específico
    public function listarPorUsuario($usuarioId) {
        $stmt = $this->pdo->prepare("SELECT * FROM terrenos WHERE usuario_id = ?");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Crear un terreno asociado a un usuario
    public function crear($usuarioId, $ubicacion, $tamaño) {
        $stmt = $this->pdo->prepare("INSERT INTO terrenos (usuario_id, ubicacion, tamaño, fecha_registro) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$usuarioId, $ubicacion, $tamaño]);
        return $this->pdo->lastInsertId();
    }

    // Actualizar un terreno si pertenece al usuario
    public function actualizar($id, $usuarioId, $ubicacion, $tamaño) {
        $stmt = $this->pdo->prepare("UPDATE terrenos SET ubicacion = ?, tamaño = ? WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$ubicacion, $tamaño, $id, $usuarioId]);
        return $stmt->rowCount() > 0;
    }

    // Eliminar un terreno si pertenece al usuario
    public function eliminar($id, $usuarioId) {
        $stmt = $this->pdo->prepare("DELETE FROM terrenos WHERE id = ? AND usuario_id = ?");
        $stmt->execute([$id, $usuarioId]);
        return $stmt->rowCount() > 0;
    }
}
?>
