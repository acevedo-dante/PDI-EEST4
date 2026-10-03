<?php

use Slim\App;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../controllers/AuthController.php';

return function (App $app, PhpRenderer $renderer) {

    $controller = new AuthController();

    // GET /auth/register
    $app->get('/auth/register', function (Request $request, Response $response) use ($controller, $renderer) {
        return $controller->showRegister($request, $response, $renderer);
    });

    // POST /auth/register
    $app->post('/auth/register', function (Request $request, Response $response) use ($controller, $renderer) {
        return $controller->register($request, $response, $renderer);
    });

    // GET /auth/login
    $app->get('/auth/login', function (Request $request, Response $response) use ($controller, $renderer) {
        return $controller->showLogin($request, $response, $renderer);
    });

    // POST /auth/login
    $app->post('/auth/login', function (Request $request, Response $response) use ($controller, $renderer) {
        return $controller->login($request, $response, $renderer);
    });

    // POST /auth/logout
    $app->post('/auth/logout', function (Request $request, Response $response) use ($controller) {
        return $controller->logout($request, $response);
    });

};
