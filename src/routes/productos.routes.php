<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;

$app->get('/productos', function (Request $request, Response $response) use ($renderer, $database) {
    $productos = $database->query("SELECT * FROM productos")->fetchAll();

    return $renderer->render($response, 'productos/index.php', [
        'productos' => $productos
    ]);
});

$app->get('/productos/', function (Request $request, Response $response) use ($renderer, $database) {
    $productos = $database->query("SELECT * FROM productos")->fetchAll();

    return $renderer->render($response, 'productos/index.php', [
        'productos' => $productos
    ]);
});

$app->get('/productos/create', function (Request $request, Response $response) use ($renderer) {
    return $renderer->render($response, 'productos/create.php');
});

$app->get('/productos/{id}', function (Request $request, Response $response, array $args) use ($renderer, $database) {
    $id = $args['id'];

    $stmt = $database->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$id]);

    $producto = $stmt->fetch();

    if (!$producto) {
        return $renderer->render($response, 'productos/not_found.php');
    }

    return $renderer->render($response, 'productos/show.php', [
        'producto' => $producto
    ]);
});

$app->get('/productos/update/{id}', function (Request $request, Response $response, array $args) use ($renderer, $database) {
    $id = $args['id'];

    $stmt = $database->prepare("SELECT * FROM productos WHERE id = ?");
    $stmt->execute([$id]);

    $producto = $stmt->fetch();

    if (!$producto) {
        return $renderer->render($response, 'productos/not_found.php');
    }

    return $renderer->render($response, 'productos/update.php', [
        'producto' => $producto
    ]);
});
