<?php

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/database/database.php';

Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

$app = AppFactory::create();

$renderer = new PhpRenderer(__DIR__ . '/views');

$database = new Database();
$pdo = $database->getConnection();


// GET /
// Página principal
$app->get('/', function ($request, $response) use ($renderer) {
    return $renderer->render($response, 'index.php');
});


// GET /productos/
// Lista todos los productos
$app->get('/productos/', function ($request, $response) use ($renderer, $pdo) {

    $stmt = $pdo->query('SELECT * FROM productos');
    $productos = $stmt->fetchAll();

    return $renderer->render($response, 'productos/index.php', [
        'productos' => $productos
    ]);
});


// GET /productos/create
// Muestra el formulario para crear
$app->get('/productos/create', function ($request, $response) use ($renderer) {

    return $renderer->render($response, 'productos/create.php');
});


// GET /productos/update/{id}
// Muestra el formulario para editar
$app->get('/productos/update/{id}', function ($request, $response, $args) use ($renderer, $pdo) {

    $id = (int) $args['id'];

    $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = ?');
    $stmt->execute([$id]);

    $producto = $stmt->fetch();

    if (!$producto) {
        return $renderer->render($response, 'productos/not_found.php');
    }

    return $renderer->render($response, 'productos/update.php', [
        'producto' => $producto
    ]);
});


// GET /productos/{id}
// Muestra un producto
$app->get('/productos/{id}', function ($request, $response, $args) use ($renderer, $pdo) {

    $id = (int) $args['id'];

    $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = ?');
    $stmt->execute([$id]);

    $producto = $stmt->fetch();

    if (!$producto) {
        return $renderer->render($response, 'productos/not_found.php');
    }

    return $renderer->render($response, 'productos/show.php', [
        'producto' => $producto
    ]);
});


// POST /productos
// Crea un producto
$app->post('/productos', function ($request, $response) use ($pdo) {

    $data = $request->getParsedBody();

    $nombre = $data['nombre'] ?? '';
    $precio = $data['precio'] ?? 0;
    $descripcion = $data['descripcion'] ?? '';
    $stock = $data['stock'] ?? 0;

    try {

        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'INSERT INTO productos (nombre, descripcion, precio, stock)
             VALUES (?, ?, ?, ?)'
        );

        $stmt->execute([
            $nombre,
            $descripcion,
            $precio,
            $stock
        ]);

        $pdo->commit();

        return $response
            ->withHeader('Location', '/productos/')
            ->withStatus(302);

    } catch (Exception $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }
});


// PUT /productos/{id}
// Actualiza un producto
$app->put('/productos/{id}', function ($request, $response, $args) use ($pdo) {

    $id = (int) $args['id'];

    $data = $request->getParsedBody();

    $nombre = $data['nombre'] ?? '';
    $precio = $data['precio'] ?? 0;
    $descripcion = $data['descripcion'] ?? '';
    $stock = $data['stock'] ?? 0;

    try {

        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'UPDATE productos
             SET nombre = ?, descripcion = ?, precio = ?, stock = ?
             WHERE id = ?'
        );

        $stmt->execute([
            $nombre,
            $descripcion,
            $precio,
            $stock,
            $id
        ]);

        $pdo->commit();

        return $response
            ->withHeader('Location', '/productos/' . $id)
            ->withStatus(302);

    } catch (Exception $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }
});


// DELETE /productos/{id}
// Elimina un producto
$app->delete('/productos/{id}', function ($request, $response, $args) use ($pdo) {

    $id = (int) $args['id'];

    try {

        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'DELETE FROM productos WHERE id = ?'
        );

        $stmt->execute([$id]);

        $pdo->commit();

        return $response->withStatus(204);

    } catch (Exception $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }
});


return $app;
