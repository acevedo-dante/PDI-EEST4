<?php

require_once __DIR__ . '/../database/database.php';

class ProductoPersistence {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getDb() {
        return $this->db;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM productos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM productos WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (:nombre, :descripcion, :precio, :stock)");
        return $stmt->execute([
            ':nombre' => $data['nombre'] ?? '',
            ':descripcion' => $data['descripcion'] ?? '',
            ':precio' => $data['precio'] ?? 0,
            ':stock' => $data['stock'] ?? 0
        ]);
    }

    public function update($id, $data) {
        $stmt = $this->db->prepare("UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':nombre' => $data['nombre'] ?? '',
            ':descripcion' => $data['descripcion'] ?? '',
            ':precio' => $data['precio'] ?? 0,
            ':stock' => $data['stock'] ?? 0
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM productos WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
