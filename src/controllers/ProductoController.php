<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Views\PhpRenderer;
use Slim\Routing\RouteContext;

require_once __DIR__ . '/../services/ProductoService.php';

class ProductoController {
    private $service;

    public function __construct() {
        $this->service = new ProductoService();
    }

    private function basePath(Request $request): string {
        return RouteContext::fromRequest($request)->getBasePath();
    }

    private function redirect(Request $request, Response $response, string $path): Response {
        return $response->withHeader('Location', $this->basePath($request) . $path)->withStatus(302);
    }

    /**
     * Valida los datos del formulario. Devuelve [datos limpios, mensaje de error|null].
     */
    private function validar($body): array {
        $data = (array) $body;
        $nombre = trim((string) ($data['nombre'] ?? ''));
        $descripcion = trim((string) ($data['descripcion'] ?? ''));
        $precio = trim((string) ($data['precio'] ?? ''));
        $stock = trim((string) ($data['stock'] ?? ''));

        $error = null;
        if ($nombre === '') {
            $error = 'El nombre es obligatorio.';
        } elseif (filter_var($precio, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false) {
            $error = 'El precio debe ser un entero mayor o igual a 0.';
        } elseif ($stock !== '' && filter_var($stock, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0]]) === false) {
            $error = 'El stock debe ser un entero mayor o igual a 0.';
        }

        return [
            ['nombre' => $nombre, 'descripcion' => $descripcion, 'precio' => $precio, 'stock' => $stock === '' ? '0' : $stock],
            $error,
        ];
    }

    // GET /productos/?limit=N
    public function index(Request $request, Response $response, PhpRenderer $renderer) {
        // ?limit=N muestra solo los primeros N elementos (entero positivo); otro valor muestra todo
        $limit = filter_var(
            $request->getQueryParams()['limit'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );

        $productos = $this->service->obtenerTodos($limit === false ? null : $limit);

        return $renderer->render($response, 'productos/index.php', [
            'productos' => $productos,
            'basePath' => $this->basePath($request),
        ]);
    }

    // GET /productos/create
    public function showCreate(Request $request, Response $response, PhpRenderer $renderer) {
        return $renderer->render($response, 'productos/create.php', [
            'basePath' => $this->basePath($request),
            'error' => null,
            'old' => [],
        ]);
    }

    // POST /productos
    public function store(Request $request, Response $response, PhpRenderer $renderer) {
        [$data, $error] = $this->validar($request->getParsedBody());

        if ($error !== null) {
            return $renderer->render($response->withStatus(422), 'productos/create.php', [
                'basePath' => $this->basePath($request),
                'error' => $error,
                'old' => $data,
            ]);
        }

        try {
            $this->service->crearProducto($data);
            return $this->redirect($request, $response, '/productos/');
        } catch (\Exception $e) {
            $response->getBody()->write('No se pudo crear el producto.');
            return $response->withStatus(500);
        }
    }

    // GET /productos/{id}
    public function show(Request $request, Response $response, array $args, PhpRenderer $renderer) {
        $producto = $this->service->obtenerPorId($args['id']);
        $basePath = $this->basePath($request);

        if (!$producto) {
            return $renderer->render($response->withStatus(404), 'productos/not_found.php', ['basePath' => $basePath]);
        }
        return $renderer->render($response, 'productos/show.php', ['producto' => $producto, 'basePath' => $basePath]);
    }

    // GET /productos/update/{id}
    public function showUpdate(Request $request, Response $response, array $args, PhpRenderer $renderer) {
        $producto = $this->service->obtenerPorId($args['id']);
        $basePath = $this->basePath($request);

        if (!$producto) {
            return $renderer->render($response->withStatus(404), 'productos/not_found.php', ['basePath' => $basePath]);
        }
        return $renderer->render($response, 'productos/update.php', [
            'producto' => $producto,
            'basePath' => $basePath,
            'error' => null,
        ]);
    }

    // PUT /productos/{id}
    public function update(Request $request, Response $response, array $args, PhpRenderer $renderer) {
        $producto = $this->service->obtenerPorId($args['id']);
        $basePath = $this->basePath($request);

        if (!$producto) {
            return $renderer->render($response->withStatus(404), 'productos/not_found.php', ['basePath' => $basePath]);
        }

        [$data, $error] = $this->validar($request->getParsedBody());

        if ($error !== null) {
            return $renderer->render($response->withStatus(422), 'productos/update.php', [
                'producto' => array_merge($producto, $data),
                'basePath' => $basePath,
                'error' => $error,
            ]);
        }

        try {
            $this->service->actualizarProducto($args['id'], $data);
            return $this->redirect($request, $response, '/productos/' . (int) $args['id']);
        } catch (\Exception $e) {
            $response->getBody()->write('No se pudo actualizar el producto.');
            return $response->withStatus(500);
        }
    }

    // DELETE /productos/{id}
    public function delete(Request $request, Response $response, array $args) {
        try {
            $this->service->eliminarProducto($args['id']);
            return $this->redirect($request, $response, '/productos/');
        } catch (\Exception $e) {
            $response->getBody()->write('No se pudo eliminar el producto.');
            return $response->withStatus(500);
        }
    }
}
