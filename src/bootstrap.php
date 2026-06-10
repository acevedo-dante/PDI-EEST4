<?php

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

// Cargar variables de entorno desde el .env
Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

// Crear la aplicacion de Slim
$app = AppFactory::create();

// Crear el motor de plantillas
$renderer = new PhpRenderer(__DIR__ . '/views');

// Ruta/Vista principal
$app->get('/', function ($request, $response) use ($renderer) {
  return $renderer->render($response, 'index.php');
});

// Listado de Productos
$app->get('/productos', function ($request, $response) use ($renderer) {
  return $renderer->render($response, 'productos/index.php');
});

// Detalle de un Producto
$app->get('/productos/{id}', function ($request, $response, $args) use ($renderer) {
  return $renderer->render($response, 'productos/show.php', [
    'id' => $args['id']
  ]);
});

// Crear Producto
$app->get('/create/productos', function ($request, $response) use ($renderer) {
  return $renderer->render($response, 'productos/store.php');
});

return $app;
