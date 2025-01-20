<?php
require_once __DIR__ . '/../config/db.php';

class Producto {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function listar() {
        $stmt = $this->pdo->query("SELECT * FROM productos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function crear($nombre, $categoria, $rentabilidad) {
        $stmt = $this->pdo->prepare("INSERT INTO productos (nombre, categoria, rentabilidad) VALUES (?, ?, ?)");
        $stmt->execute([$nombre, $categoria, $rentabilidad]);
        return $this->pdo->lastInsertId();
    }

    public function actualizar($id, $nombre, $categoria, $rentabilidad) {
        $stmt = $this->pdo->prepare("UPDATE productos SET nombre = ?, categoria = ?, rentabilidad = ? WHERE id = ?");
        $stmt->execute([$nombre, $categoria, $rentabilidad, $id]);
        return $stmt->rowCount() > 0;
    }

    public function eliminar($id) {
        $stmt = $this->pdo->prepare("DELETE FROM productos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
?>
