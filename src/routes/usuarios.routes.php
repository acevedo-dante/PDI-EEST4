<?php

use Slim\App;
use Slim\Views\PhpRenderer;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (App $app, PhpRenderer $renderer) {

    // Todas las rutas de usuarios requieren sesión iniciada
    $app->group('', function (RouteCollectorProxy $group) use ($renderer) {

        // Listar usuarios
        $group->get('/usuarios/', function (Request $request, Response $response) use ($renderer) {
            return $renderer->render($response, 'usuarios/index.php');
        });

        // Mostrar un usuario específico
        $group->get('/usuarios/{id}', function (Request $request, Response $response, array $args) use ($renderer) {
            $id = $args['id'];
            return $renderer->render($response, 'usuarios/show.php', ['id' => $id]);
        });

    })->add('authMiddleware');

};
