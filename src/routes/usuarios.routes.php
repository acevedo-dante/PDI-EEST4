<?php

use Slim\App;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (App $app, PhpRenderer $renderer) {

    // Listar usuarios
    $app->get('/usuarios/', function (Request $request, Response $response) use ($renderer) {
        return $renderer->render($response, 'usuarios/index.php');
    });

    // Mostrar un usuario específico
    $app->get('/usuarios/{id}', function (Request $request, Response $response, array $args) use ($renderer) {
        $id = $args['id'];
        return $renderer->render($response, 'usuarios/show.php', ['id' => $id]);
    });

};
