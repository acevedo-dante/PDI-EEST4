<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use Slim\Routing\RouteContext;

require_once __DIR__ . '/../services/EscaneoService.php';

class EscaneoController {
    private const HOST_POR_DEFECTO = '127.0.0.1';
    private const PUERTOS_POR_DEFECTO = '1-1024';
    private const TIMEOUT_POR_DEFECTO = '0.5';

    private $service;

    public function __construct() {
        $this->service = new EscaneoService();
    }

    private function basePath(Request $request): string {
        return RouteContext::fromRequest($request)->getBasePath();
    }

    private function validar($body): array {
        $data = (array) $body;
        $host = trim((string) ($data['host'] ?? self::HOST_POR_DEFECTO));
        $puertos = trim((string) ($data['puertos'] ?? self::PUERTOS_POR_DEFECTO));
        $timeout = trim((string) ($data['timeout'] ?? self::TIMEOUT_POR_DEFECTO));

        $error = null;
        if ($host === '' || strlen($host) > 253 || !preg_match('/^[A-Za-z0-9.\-]+$/', $host)) {
            $error = 'Ingresá un host válido (por ejemplo 127.0.0.1 o localhost).';
        } elseif ($puertos === '' || !preg_match('/^[0-9,\- ]+$/', $puertos)) {
            $error = 'Los puertos deben ser números, rangos (1-1024) o listas separadas por comas.';
        } elseif (!is_numeric($timeout) || (float) $timeout < 0.05 || (float) $timeout > 5) {
            $error = 'El timeout debe estar entre 0.05 y 5 segundos.';
        }

        return [['host' => $host, 'puertos' => $puertos, 'timeout' => $timeout], $error];
    }

    private function formulario(): array {
        return [
            'host' => self::HOST_POR_DEFECTO,
            'puertos' => self::PUERTOS_POR_DEFECTO,
            'timeout' => self::TIMEOUT_POR_DEFECTO,
        ];
    }

    // GET /escaneo
    public function index(Request $request, Response $response, PhpRenderer $renderer) {
        return $renderer->render($response, 'escaneo/index.php', [
            'basePath' => $this->basePath($request),
            'form' => $this->formulario(),
            'resultado' => null,
            'error' => null,
        ]);
    }

    // POST /escaneo
    public function escanear(Request $request, Response $response, PhpRenderer $renderer) {
        $basePath = $this->basePath($request);
        [$form, $error] = $this->validar($request->getParsedBody());

        if ($error !== null) {
            return $renderer->render($response->withStatus(422), 'escaneo/index.php', [
                'basePath' => $basePath,
                'form' => $form,
                'resultado' => null,
                'error' => $error,
            ]);
        }

        try {
            $resultado = $this->service->escanear($form['host'], $form['puertos'], (float) $form['timeout']);
        } catch (\InvalidArgumentException $e) {
            return $renderer->render($response->withStatus(422), 'escaneo/index.php', [
                'basePath' => $basePath,
                'form' => $form,
                'resultado' => null,
                'error' => $e->getMessage(),
            ]);
        } catch (\Exception $e) {
            return $renderer->render($response->withStatus(500), 'escaneo/index.php', [
                'basePath' => $basePath,
                'form' => $form,
                'resultado' => null,
                'error' => 'No se pudo ejecutar el escaneo. Revisá que Python esté instalado y configurado en PYTHON_BIN.',
            ]);
        }

        return $renderer->render($response, 'escaneo/index.php', [
            'basePath' => $basePath,
            'form' => $form,
            'resultado' => $resultado,
            'error' => null,
        ]);
    }
}
