<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;

$app->get('/usuarios', function (Request $request, Response $response) use ($renderer, $database) {
    $usuarios = $database->query("SELECT * FROM usuarios")->fetchAll();

    return $renderer->render($response, 'usuarios/index.php', [
        'usuarios' => $usuarios
    ]);
});

$app->get('/usuarios/create', function (Request $request, Response $response) use ($renderer) {
    return $renderer->render($response, 'usuarios/create.php');
});

$app->get('/usuarios/{id}', function (Request $request, Response $response, array $args) use ($renderer, $database) {
    $id = $args['id'];

    $stmt = $database->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);

    $usuario = $stmt->fetch();

    if (!$usuario) {
        return $renderer->render($response, 'usuarios/not_found.php');
    }

    return $renderer->render($response, 'usuarios/show.php', [
        'usuario' => $usuario
    ]);
});

$app->get('/usuarios/update/{id}', function (Request $request, Response $response, array $args) use ($renderer, $database) {
    $id = $args['id'];

    $stmt = $database->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);

    $usuario = $stmt->fetch();

    if (!$usuario) {
        return $renderer->render($response, 'usuarios/not_found.php');
    }

    return $renderer->render($response, 'usuarios/update.php', [
        'usuario' => $usuario
    ]);
});
