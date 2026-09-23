<?php

use Slim\App;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

return function (App $app, PhpRenderer $renderer) {

    // Mostrar formulario de login
    $app->get('/login', function (Request $request, Response $response) use ($renderer) {
        return $renderer->render($response, 'auth/login.php');
    });

    // Procesar inicio de sesión
    $app->post('/login', function (Request $request, Response $response) {
        // Lógica de autenticación
        return $response->withHeader('Location', '/PDI-EEST4-main/public/productos/')->withStatus(302);
    });

    // Cerrar sesión
    $app->post('/logout', function (Request $request, Response $response) {
        // Lógica de logout
        return $response->withHeader('Location', '/PDI-EEST4-main/public/login')->withStatus(302);
    });

};
