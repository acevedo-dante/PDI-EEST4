<?php

require_once __DIR__ . '/../persistence/EscaneoPersistence.php';

class EscaneoService {
    private const PUERTOS_RIESGOSOS = [
        21 => 'FTP: transmite usuario y contraseña sin cifrar',
        23 => 'Telnet: acceso remoto sin cifrar',
        135 => 'RPC de Windows: expuesto suele ser vector de ataques',
        139 => 'NetBIOS: comparte información de la red',
        445 => 'SMB: objetivo frecuente de malware y ransomware',
        1433 => 'SQL Server: base de datos expuesta',
        3306 => 'MySQL: base de datos expuesta',
        3389 => 'Escritorio remoto (RDP): objetivo frecuente de fuerza bruta',
        5432 => 'PostgreSQL: base de datos expuesta',
    ];

    private $persistence;

    public function __construct() {
        $this->persistence = new EscaneoPersistence();
    }

    public function escanear(string $host, string $puertos, float $timeout): array {
        $resultado = $this->persistence->escanear($host, $puertos, $timeout);

        if (isset($resultado['error'])) {
            throw new \InvalidArgumentException($resultado['error']);
        }

        $resultado['puertos'] = array_map(function ($puerto) {
            $puerto['riesgo'] = self::PUERTOS_RIESGOSOS[$puerto['puerto']] ?? null;
            return $puerto;
        }, $resultado['puertos'] ?? []);

        $resultado['riesgosos'] = count(array_filter($resultado['puertos'], fn($p) => $p['riesgo'] !== null));

        return $resultado;
    }
}
