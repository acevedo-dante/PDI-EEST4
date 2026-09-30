<?php

use Slim\App;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../controllers/ProductoController.php';

return function (App $app, PhpRenderer $renderer) {

    $controller = new ProductoController();

    // GET /productos/
    $app->get('/productos/', function (Request $request, Response $response) use ($controller, $renderer) {
        return $controller->index($request, $response, $renderer);
    });

    // GET /productos/create
    $app->get('/productos/create', function (Request $request, Response $response) use ($controller, $renderer) {
        return $controller->showCreate($request, $response, $renderer);
    });

    // POST /productos
    $app->post('/productos', function (Request $request, Response $response) use ($controller) {
        return $controller->store($request, $response);
    });

    // GET /productos/update/{id}
    $app->get('/productos/update/{id}', function (Request $request, Response $response, array $args) use ($controller, $renderer) {
        return $controller->showUpdate($request, $response, $args, $renderer);
    });

    // PUT /productos/{id}
    $app->put('/productos/{id}', function (Request $request, Response $response, array $args) use ($controller) {
        return $controller->update($request, $response, $args);
    });

    // GET /productos/{id}
    $app->get('/productos/{id}', function (Request $request, Response $response, array $args) use ($controller, $renderer) {
        return $controller->show($request, $response, $args, $renderer);
    });

    // DELETE /productos/{id}
    $app->delete('/productos/{id}', function (Request $request, Response $response, array $args) use ($controller) {
        return $controller->delete($request, $response, $args);
    });

};
