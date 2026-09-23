<?php

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno .env si tenés phpdotenv
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

// Crear la aplicación Slim
$app = AppFactory::create();

// Establecer BasePath si la app está en una subcarpeta
$app->setBasePath('/PDI-EEST4-main/public');

// Cargar modularizadamente los archivos de rutas pasándoles la variable $app
(require __DIR__ . '/routes/auth.routes.php')($app);
(require __DIR__ . '/routes/usuarios.routes.php')($app);
(require __DIR__ . '/routes/productos.routes.php')($app);

return $app;
