<?php

use Slim\App;
use Slim\Views\PhpRenderer;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../controllers/UsuarioController.php';

return function (App $app, PhpRenderer $renderer) {

    $controller = new UsuarioController();

    // Todas las rutas de usuarios requieren sesión iniciada
    $app->group('', function (RouteCollectorProxy $group) use ($controller, $renderer) {

        // GET /usuarios/
        $group->get('/usuarios/', function (Request $request, Response $response) use ($controller, $renderer) {
            return $controller->index($request, $response, $renderer);
        });

        // GET /usuarios/{id}
        $group->get('/usuarios/{id}', function (Request $request, Response $response, array $args) use ($controller, $renderer) {
            return $controller->show($request, $response, $args, $renderer);
        });

    })->add('authMiddleware');

};
