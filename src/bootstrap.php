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

// Ruta principal
$app->get('/', function ($request, $response) use ($renderer) {
  return $renderer->render($response, 'index.php');
});

  // ARRAY DE PRODUCTOS
  $productos = [
    ['id' => 1, 'name' => 'Camiseta de futbol', 'price' => 15000],
    ['id' => 2, 'name' => 'Botines', 'price' => 45000],
    ['id' => 3, 'name' => 'Pelota', 'price' => 2000],
    ['id' => 4, 'name' => 'Guantes de arquero', 'price' => 8000],
    ['id' => 5, 'name' => 'Medias deportivas', 'price' => 3000]
  ];

  // ?limit=
  $queryParams = $request->getQueryParams();
  $limit = isset($queryParams['limit']) ? (int)$queryParams['limit'] : null;

  if ($limit) {
    $productos = array_slice($productos, 0, $limit);
  }

  return $renderer->render($response, 'productos/index.php', [
    'productos' => $productos
  ]);
});

// DETALLE DE PRODUCTO
$app->get('/productos/{id}', function ($request, $response, $args) use ($renderer) {

  $productos = [
    ['id' => 1, 'name' => 'Camiseta de futbol', 'price' => 15000],
    ['id' => 2, 'name' => 'Botines', 'price' => 45000],
    ['id' => 3, 'name' => 'Pelota', 'price' => 2000],
    ['id' => 4, 'name' => 'Guantes de arquero', 'price' => 8000],
    ['id' => 5, 'name' => 'Medias deportivas', 'price' => 3000]
  ];

  $id = (int)$args['id'];
  $producto = null;

  foreach ($productos as $p) {
    if ($p['id'] === $id) {
      $producto = $p;
      break;
    }
  }

  return $renderer->render($response, 'productos/show.php', [
    'producto' => $producto
  ]);
});

return $app;
