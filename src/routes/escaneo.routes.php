<?php

use Slim\App;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../controllers/EscaneoController.php';

return function (App $app, PhpRenderer $renderer) {

    $controller = new EscaneoController();

    // GET /escaneo
    $app->get('/escaneo', function (Request $request, Response $response) use ($controller, $renderer) {
        return $controller->index($request, $response, $renderer);
    });

    // POST /escaneo
    $app->post('/escaneo', function (Request $request, Response $response) use ($controller, $renderer) {
        return $controller->escanear($request, $response, $renderer);
    });

};
