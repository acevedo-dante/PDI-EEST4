<?php

require_once __DIR__ . '/../database/database.php';

class UsuarioPersistence {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function getDb() {
        return $this->db;
    }

    // El hash de la contraseña no se expone en los listados
    public function getAll() {
        $stmt = $this->db->query("SELECT id, nombre, email FROM usuarios ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT id, nombre, email FROM usuarios WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Incluye el hash de la contraseña: se usa para autenticar
    public function getByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data) {
        $stmt = $this->db->prepare("INSERT INTO usuarios (nombre, email, password) VALUES (:nombre, :email, :password)");
        return $stmt->execute([
            ':nombre' => $data['nombre'],
            ':email' => $data['email'],
            ':password' => $data['password']
        ]);
    }
}
