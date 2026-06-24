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


  // 1. ARRAY ASOCIATIVO (productos)
  $productos = [
    ['id' => 1, 'name' => 'Camiseta de futbol', 'price' => 15000],
    ['id' => 2, 'name' => 'Botines', 'price' => 45000],
    ['id' => 3, 'name' => 'Pelota', 'price' => 2000],
    ['id' => 4, 'name' => 'Guantes de arquero', 'price' => 8000],
    ['id' => 5, 'name' => 'Medias deportivas', 'price' => 3000]
  ];

  // 2. TOMAR QUERY PARAM (?limit=2)
  $queryParams = $request->getQueryParams();
  $limit = isset($queryParams['limit']) ? (int)$queryParams['limit'] : null;

  // 3. APLICAR LIMITE SI EXISTE
  if ($limit) {
    $productos = array_slice($productos, 0, $limit);
  }

  // 4. ENVIAR A LA VISTA
  return $renderer->render($response, 'productos/index.php', [
    'productos' => $productos
  ]);
});
return $app;
