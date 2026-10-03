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

    public function index(Request $request, Response $response, PhpRenderer $renderer) {
        // Listado de ejemplo: array asociativo (id, name, price)
        $productos = [
            ['id' => 1, 'name' => 'Camiseta de futbol', 'price' => 15000],
            ['id' => 2, 'name' => 'Botines', 'price' => 45000],
            ['id' => 3, 'name' => 'Pelota', 'price' => 2000],
            ['id' => 4, 'name' => 'Canilleras', 'price' => 5000],
            ['id' => 5, 'name' => 'Guantes de arquero', 'price' => 12000],
        ];

        // ?limit=N muestra solo los primeros N elementos (entero positivo)
        $limit = filter_var(
            $request->getQueryParams()['limit'] ?? null,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]]
        );
        if ($limit !== false) {
            $productos = array_slice($productos, 0, $limit);
        }

        return $renderer->render($response, 'productos/index.php', [
            'productos' => $productos,
            'basePath' => RouteContext::fromRequest($request)->getBasePath(),
        ]);
    }

    public function showCreate(Request $request, Response $response, PhpRenderer $renderer) {
        return $renderer->render($response, 'productos/create.php');
    }

    public function store(Request $request, Response $response) {
        $data = $request->getParsedBody() ?? $_REQUEST;
        try {
            $this->service->crearProducto($data);
            return $response->withHeader('Location', '/PDI-EEST4-main/public/productos/')->withStatus(302);
        } catch (\Exception $e) {
            return $response->withStatus(500);
        }
    }

    public function show(Request $request, Response $response, array $args, PhpRenderer $renderer) {
        $producto = $this->service->obtenerPorId($args['id']);
        if (!$producto) {
            return $renderer->render($response, 'productos/not_found.php');
        }
        return $renderer->render($response, 'productos/show.php', ['producto' => $producto]);
    }

    public function showUpdate(Request $request, Response $response, array $args, PhpRenderer $renderer) {
        $producto = $this->service->obtenerPorId($args['id']);
        if (!$producto) {
            return $renderer->render($response, 'productos/not_found.php');
        }
        return $renderer->render($response, 'productos/update.php', ['producto' => $producto]);
    }

    public function update(Request $request, Response $response, array $args) {
        $data = $request->getParsedBody() ?? $_REQUEST;
        try {
            $this->service->actualizarProducto($args['id'], $data);
            return $response->withHeader('Location', '/PDI-EEST4-main/public/productos/')->withStatus(302);
        } catch (\Exception $e) {
            return $response->withStatus(500);
        }
    }

    public function delete(Request $request, Response $response, array $args) {
        try {
            $this->service->eliminarProducto($args['id']);
            return $response->withHeader('Location', '/PDI-EEST4-main/public/productos/')->withStatus(302);
        } catch (\Exception $e) {
            return $response->withStatus(500);
        }
    }
}
