<?php

use Slim\App;
use Slim\Views\PhpRenderer;
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../controllers/ProductoController.php';

return function (App $app, PhpRenderer $renderer) {

    $controller = new ProductoController();

    // Todas las rutas de productos requieren sesión iniciada
    $app->group('', function (RouteCollectorProxy $group) use ($controller, $renderer) {

        // GET /productos/
        $group->get('/productos/', function (Request $request, Response $response) use ($controller, $renderer) {
            return $controller->index($request, $response, $renderer);
        });

        // GET /productos/create
        $group->get('/productos/create', function (Request $request, Response $response) use ($controller, $renderer) {
            return $controller->showCreate($request, $response, $renderer);
        });

        // POST /productos
        $group->post('/productos', function (Request $request, Response $response) use ($controller, $renderer) {
            return $controller->store($request, $response, $renderer);
        });

        // GET /productos/update/{id}
        $group->get('/productos/update/{id}', function (Request $request, Response $response, array $args) use ($controller, $renderer) {
            return $controller->showUpdate($request, $response, $args, $renderer);
        });

        // PUT /productos/{id}
        $group->put('/productos/{id}', function (Request $request, Response $response, array $args) use ($controller) {
            return $controller->update($request, $response, $args);
        });

        // GET /productos/{id}
        $group->get('/productos/{id}', function (Request $request, Response $response, array $args) use ($controller, $renderer) {
            return $controller->show($request, $response, $args, $renderer);
        });

        // DELETE /productos/{id}
        $group->delete('/productos/{id}', function (Request $request, Response $response, array $args) use ($controller) {
            return $controller->delete($request, $response, $args);
        });

    })->add('authMiddleware');

};
