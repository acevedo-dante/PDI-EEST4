<?php

use Slim\Psr7\Request;
use Slim\Psr7\Response;

$app->get('/login', function (Request $request, Response $response) use ($renderer) {
    return $renderer->render($response, 'login.php');
});

$app->post('/login', function (Request $request, Response $response) use ($database) {
    $data = $request->getParsedBody();

    $email = $data['email'];
    $password = $data['password'];

    $stmt = $database->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);

    $usuario = $stmt->fetch();

    if ($usuario && $usuario['password'] === $password) {
        return $response
            ->withHeader('Location', '/PDI-EEST4-main/public/')
            ->withStatus(302);
    }

    return $response
        ->withHeader('Location', '/PDI-EEST4-main/public/login')
        ->withStatus(302);
});
