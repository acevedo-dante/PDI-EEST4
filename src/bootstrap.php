<?php

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Slim\Middleware\MethodOverrideMiddleware;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

$app = AppFactory::create();
$app->setBasePath('/PDI-EEST4-main/public');

$renderer = new PhpRenderer(__DIR__ . '/views');

// Middlewares
require __DIR__ . '/middleware/auth.php';
require __DIR__ . '/middleware/log.php';

// Middleware global: se ejecuta en todas las peticiones
$app->add('logMiddleware');
// Los formularios envían PUT/DELETE como POST con el campo oculto _METHOD.
// Se agrega ANTES del body parsing para que este se ejecute primero (los middlewares corren en orden inverso).
$app->add(new MethodOverrideMiddleware());
$app->addBodyParsingMiddleware();

// Cargar rutas modularizadas
(require __DIR__ . '/routes/auth.routes.php')($app, $renderer);
(require __DIR__ . '/routes/usuarios.routes.php')($app, $renderer);
(require __DIR__ . '/routes/productos.routes.php')($app, $renderer);

return $app;
