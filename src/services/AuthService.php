<?php

require_once __DIR__ . '/../persistence/UsuarioPersistence.php';

class AuthService {
    private $persistence;

    public function __construct() {
        $this->persistence = new UsuarioPersistence();
    }

    /**
     * Registra un usuario nuevo (en una transacción).
     * @throws DomainException si el email ya está en uso.
     */
    public function registrar($nombre, $email, $password) {
        $db = $this->persistence->getDb();
        try {
            $db->beginTransaction();

            if ($this->persistence->getByEmail($email)) {
                throw new DomainException('Ya existe una cuenta con ese email.');
            }

            $this->persistence->create([
                'nombre' => $nombre,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);

            $db->commit();
        } catch (\Exception $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            throw $e;
        }
    }

    /**
     * Verifica las credenciales y devuelve el usuario (sin el hash).
     * @throws DomainException si el email o la contraseña son incorrectos.
     */
    public function autenticar($email, $password) {
        $usuario = $this->persistence->getByEmail($email);

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            throw new DomainException('Email o contraseña incorrectos.');
        }

        return ['id' => $usuario['id'], 'nombre' => $usuario['nombre'], 'email' => $usuario['email']];
    }
}
