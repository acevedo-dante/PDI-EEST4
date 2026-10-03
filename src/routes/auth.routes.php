<?php

use Slim\App;
use Slim\Views\PhpRenderer;
use Slim\Routing\RouteContext;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../persistence/UsuarioPersistence.php';

return function (App $app, PhpRenderer $renderer) {

    $persistence = new UsuarioPersistence();

    $basePath = function (Request $request): string {
        return RouteContext::fromRequest($request)->getBasePath();
    };

    // GET /auth/register
    $app->get('/auth/register', function (Request $request, Response $response) use ($renderer, $basePath) {
        return $renderer->render($response, 'auth/register.php', [
            'basePath' => $basePath($request),
            'error' => null,
            'old' => [],
        ]);
    });

    // POST /auth/register
    $app->post('/auth/register', function (Request $request, Response $response) use ($renderer, $persistence, $basePath) {
        $data = (array) $request->getParsedBody();
        $nombre = trim((string) ($data['name'] ?? ''));
        $email = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        $error = null;
        if ($nombre === '' || $email === '' || $password === '') {
            $error = 'Completá todos los campos.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'El email no es válido.';
        } elseif (strlen($password) < 8) {
            $error = 'La contraseña debe tener al menos 8 caracteres.';
        } elseif ($persistence->getByEmail($email)) {
            $error = 'Ya existe una cuenta con ese email.';
        }

        if ($error !== null) {
            return $renderer->render($response->withStatus(422), 'auth/register.php', [
                'basePath' => $basePath($request),
                'error' => $error,
                'old' => ['name' => $nombre, 'email' => $email],
            ]);
        }

        $persistence->create([
            'nombre' => $nombre,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        return $response->withHeader('Location', $basePath($request) . '/auth/login')->withStatus(302);
    });

    // GET /auth/login
    $app->get('/auth/login', function (Request $request, Response $response) use ($renderer, $basePath) {
        return $renderer->render($response, 'auth/login.php', [
            'basePath' => $basePath($request),
            'error' => null,
            'old' => [],
        ]);
    });

    // POST /auth/login
    $app->post('/auth/login', function (Request $request, Response $response) use ($renderer, $persistence, $basePath) {
        $data = (array) $request->getParsedBody();
        $email = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        $usuario = $email !== '' ? $persistence->getByEmail($email) : false;

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            return $renderer->render($response->withStatus(401), 'auth/login.php', [
                'basePath' => $basePath($request),
                'error' => 'Email o contraseña incorrectos.',
                'old' => ['email' => $email],
            ]);
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_regenerate_id(true);
        $_SESSION['user_id'] = $usuario['id'];

        return $response->withHeader('Location', $basePath($request) . '/productos/')->withStatus(302);
    });

    // POST /auth/logout
    $app->post('/auth/logout', function (Request $request, Response $response) use ($basePath) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();

        return $response->withHeader('Location', $basePath($request) . '/auth/login')->withStatus(302);
    });

};
