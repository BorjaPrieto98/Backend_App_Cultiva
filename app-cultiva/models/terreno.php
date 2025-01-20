<?php
require_once __DIR__ . '/../config/db.php';

class Terreno {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listarPorUsuario($usuarioId) {
        $stmt = $this->pdo->prepare("SELECT * FROM terrenos WHERE usuario_id = ?");
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($usuarioId, $ubicacion, $tamaño) {
        $stmt = $this->pdo->prepare("INSERT INTO terrenos (usuario_id, ubicacion, tamaño, fecha_registro) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$usuarioId, $ubicacion, $tamaño]);
        return $this->pdo->lastInsertId();
    }
}
?>
