<?php

require_once __DIR__ . '/../persistence/UsuarioPersistence.php';

class UsuarioService {
    private $persistence;

    public function __construct() {
        $this->persistence = new UsuarioPersistence();
    }

    public function obtenerTodos() {
        return $this->persistence->getAll();
    }

    public function obtenerPorId($id) {
        return $this->persistence->getById($id);
    }
}
