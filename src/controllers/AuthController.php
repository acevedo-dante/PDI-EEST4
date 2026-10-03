<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use Slim\Routing\RouteContext;

require_once __DIR__ . '/../services/AuthService.php';

class AuthController {
    private $service;

    public function __construct() {
        $this->service = new AuthService();
    }

    private function basePath(Request $request): string {
        return RouteContext::fromRequest($request)->getBasePath();
    }

    private function redirect(Request $request, Response $response, string $path): Response {
        return $response->withHeader('Location', $this->basePath($request) . $path)->withStatus(302);
    }

    // GET /auth/register
    public function showRegister(Request $request, Response $response, PhpRenderer $renderer) {
        return $renderer->render($response, 'auth/register.php', [
            'basePath' => $this->basePath($request),
            'error' => null,
            'old' => [],
        ]);
    }

    // POST /auth/register
    public function register(Request $request, Response $response, PhpRenderer $renderer) {
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
        } else {
            try {
                $this->service->registrar($nombre, $email, $password);
                return $this->redirect($request, $response, '/auth/login');
            } catch (\DomainException $e) {
                $error = $e->getMessage();
            } catch (\Exception $e) {
                $response->getBody()->write('No se pudo registrar el usuario.');
                return $response->withStatus(500);
            }
        }

        return $renderer->render($response->withStatus(422), 'auth/register.php', [
            'basePath' => $this->basePath($request),
            'error' => $error,
            'old' => ['name' => $nombre, 'email' => $email],
        ]);
    }

    // GET /auth/login
    public function showLogin(Request $request, Response $response, PhpRenderer $renderer) {
        return $renderer->render($response, 'auth/login.php', [
            'basePath' => $this->basePath($request),
            'error' => null,
            'old' => [],
        ]);
    }

    // POST /auth/login
    public function login(Request $request, Response $response, PhpRenderer $renderer) {
        $data = (array) $request->getParsedBody();
        $email = trim((string) ($data['email'] ?? ''));
        $password = (string) ($data['password'] ?? '');

        try {
            if ($email === '' || $password === '') {
                throw new \DomainException('Email o contraseña incorrectos.');
            }
            $usuario = $this->service->autenticar($email, $password);
        } catch (\DomainException $e) {
            return $renderer->render($response->withStatus(401), 'auth/login.php', [
                'basePath' => $this->basePath($request),
                'error' => $e->getMessage(),
                'old' => ['email' => $email],
            ]);
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        session_regenerate_id(true);
        $_SESSION['user_id'] = $usuario['id'];

        return $this->redirect($request, $response, '/productos/');
    }

    // POST /auth/logout
    public function logout(Request $request, Response $response) {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();

        return $this->redirect($request, $response, '/auth/login');
    }
}
