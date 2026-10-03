<?php

require_once __DIR__ . '/../database/database.php';

class UsuarioPersistence {
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

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
