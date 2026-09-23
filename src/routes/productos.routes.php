<?php

use Slim\App;
use Slim\Views\PhpRenderer;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../database/database.php';

return function (App $app, PhpRenderer $renderer) {

    // READ - Listar todos los productos
    $app->get('/productos/', function (Request $request, Response $response) use ($renderer) {
        $db = (new Database())->getConnection();
        $stmt = $db->query("SELECT * FROM productos");
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $renderer->render($response, 'productos/index.php', ['productos' => $productos]);
    });

    // CREATE - Mostrar formulario de creación
    $app->get('/productos/create', function (Request $request, Response $response) use ($renderer) {
        return $renderer->render($response, 'productos/create.php');
    });

    // CREATE - Guardar producto nuevo (con Transacción)
    $app->post('/productos', function (Request $request, Response $response) {
        $db = (new Database())->getConnection();
        $data = $request->getParsedBody() ?? $_REQUEST;

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("INSERT INTO productos (nombre, descripcion, precio, stock) VALUES (:nombre, :descripcion, :precio, :stock)");
            $stmt->execute([
                ':nombre' => $data['nombre'] ?? '',
                ':descripcion' => $data['descripcion'] ?? '',
                ':precio' => $data['precio'] ?? 0,
                ':stock' => $data['stock'] ?? 0
            ]);

            $db->commit();
            return $response->withHeader('Location', '/PDI-EEST4-main/public/productos/')->withStatus(302);
        } catch (\Exception $e) {
            $db->rollBack();
            return $response->withStatus(500);
        }
    });

    // UPDATE - Mostrar formulario de edición
    $app->get('/productos/update/{id}', function (Request $request, Response $response, array $args) use ($renderer) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM productos WHERE id = :id");
        $stmt->execute([':id' => $args['id']]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            return $renderer->render($response, 'productos/not_found.php');
        }

        return $renderer->render($response, 'productos/update.php', ['producto' => $producto]);
    });

    // UPDATE - Actualizar producto existente (con Transacción)
    $app->put('/productos/{id}', function (Request $request, Response $response, array $args) {
        $db = (new Database())->getConnection();
        $data = $request->getParsedBody() ?? $_REQUEST;

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("UPDATE productos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, stock = :stock WHERE id = :id");
            $stmt->execute([
                ':id' => $args['id'],
                ':nombre' => $data['nombre'] ?? '',
                ':descripcion' => $data['descripcion'] ?? '',
                ':precio' => $data['precio'] ?? 0,
                ':stock' => $data['stock'] ?? 0
            ]);

            $db->commit();
            return $response->withHeader('Location', '/PDI-EEST4-main/public/productos/')->withStatus(302);
        } catch (\Exception $e) {
            $db->rollBack();
            return $response->withStatus(500);
        }
    });

    // READ - Mostrar un producto individual
    $app->get('/productos/{id}', function (Request $request, Response $response, array $args) use ($renderer) {
        $db = (new Database())->getConnection();
        $stmt = $db->prepare("SELECT * FROM productos WHERE id = :id");
        $stmt->execute([':id' => $args['id']]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$producto) {
            return $renderer->render($response, 'productos/not_found.php');
        }

        return $renderer->render($response, 'productos/show.php', ['producto' => $producto]);
    });

    // DELETE - Eliminar un producto (con Transacción)
    $app->delete('/productos/{id}', function (Request $request, Response $response, array $args) {
        $db = (new Database())->getConnection();

        try {
            $db->beginTransaction();

            $stmt = $db->prepare("DELETE FROM productos WHERE id = :id");
            $stmt->execute([':id' => $args['id']]);

            $db->commit();
            return $response->withHeader('Location', '/PDI-EEST4-main/public/productos/')->withStatus(302);
        } catch (\Exception $e) {
            $db->rollBack();
            return $response->withStatus(500);
        }
    });

};
