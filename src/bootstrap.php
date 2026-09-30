<?php

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
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

// Cargar rutas modularizadas
(require __DIR__ . '/routes/auth.routes.php')($app, $renderer);
(require __DIR__ . '/routes/usuarios.routes.php')($app, $renderer);
(require __DIR__ . '/routes/productos.routes.php')($app, $renderer);

return $app;
