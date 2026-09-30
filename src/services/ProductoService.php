<?php

require_once __DIR__ . '/../persistence/ProductoPersistence.php';

class ProductoService {
    private $persistence;

    public function __construct() {
        $this->persistence = new ProductoPersistence();
    }

    public function obtenerTodos() {
        return $this->persistence->getAll();
    }

    public function obtenerPorId($id) {
        return $this->persistence->getById($id);
    }

    public function crearProducto($data) {
        $db = $this->persistence->getDb();
        try {
            $db->beginTransaction();
            $resultado = $this->persistence->create($data);
            $db->commit();
            return $resultado;
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function actualizarProducto($id, $data) {
        $db = $this->persistence->getDb();
        try {
            $db->beginTransaction();
            $resultado = $this->persistence->update($id, $data);
            $db->commit();
            return $resultado;
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }

    public function eliminarProducto($id) {
        $db = $this->persistence->getDb();
        try {
            $db->beginTransaction();
            $resultado = $this->persistence->delete($id);
            $db->commit();
            return $resultado;
        } catch (\Exception $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
