<?php

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno desde .env
if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();
}

// Crear la aplicación de Slim
$app = AppFactory::create();

// Configurar el Base Path para el entorno XAMPP
$app->setBasePath('/PDI-EEST4-main/public');

// Configurar PhpRenderer para las vistas HTML/PHP
$renderer = new PhpRenderer(__DIR__ . '/views');

// Importar y ejecutar de forma modular los archivos de rutas
(require __DIR__ . '/routes/auth.routes.php')($app, $renderer);
(require __DIR__ . '/routes/usuarios.routes.php')($app, $renderer);
(require __DIR__ . '/routes/productos.routes.php')($app, $renderer);

return $app;
