<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use Slim\Routing\RouteContext;

require_once __DIR__ . '/../services/UsuarioService.php';

class UsuarioController {
    private $service;

    public function __construct() {
        $this->service = new UsuarioService();
    }

    // GET /usuarios/
    public function index(Request $request, Response $response, PhpRenderer $renderer) {
        return $renderer->render($response, 'usuarios/index.php', [
            'usuarios' => $this->service->obtenerTodos(),
            'basePath' => RouteContext::fromRequest($request)->getBasePath(),
        ]);
    }

    // GET /usuarios/{id}
    public function show(Request $request, Response $response, array $args, PhpRenderer $renderer) {
        $usuario = $this->service->obtenerPorId($args['id']);
        $basePath = RouteContext::fromRequest($request)->getBasePath();

        if (!$usuario) {
            return $renderer->render($response->withStatus(404), 'usuarios/not_found.php', ['basePath' => $basePath]);
        }
        return $renderer->render($response, 'usuarios/show.php', ['usuario' => $usuario, 'basePath' => $basePath]);
    }
}
