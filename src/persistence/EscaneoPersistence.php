<?php

class EscaneoPersistence {
    private $python;
    private $script;

    public function __construct() {
        $this->python = $_ENV['PYTHON_BIN'] ?? 'python';
        $this->script = __DIR__ . '/../../scanner/portscan.py';
    }

    public function escanear(string $host, string $puertos, float $timeout): array {
        $comando = [
            $this->python,
            $this->script,
            '--host', $host,
            '--ports', $puertos,
            '--timeout', (string) $timeout,
        ];

        $proceso = proc_open($comando, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);

        if (!is_resource($proceso)) {
            throw new \RuntimeException('No se pudo ejecutar Python.');
        }

        $salida = stream_get_contents($pipes[1]);
        $errores = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($proceso);

        $datos = json_decode((string) $salida, true);

        if (!is_array($datos)) {
            throw new \RuntimeException('El escáner no devolvió una respuesta válida: ' . trim((string) $errores));
        }

        return $datos;
    }
}
