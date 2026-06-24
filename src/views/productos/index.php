$app->get('/productos', function ($request, $response) use ($renderer) {

  $productos = [
    ['id' => 1, 'name' => 'Camiseta de futbol', 'price' => 15000],
    ['id' => 2, 'name' => 'Botines', 'price' => 45000],
    ['id' => 3, 'name' => 'Pelota', 'price' => 2000],
    ['id' => 4, 'name' => 'Medias deportivas', 'price' => 3000],
    ['id' => 5, 'name' => 'Guantes de arquero', 'price' => 8000]
  ];

  $queryParams = $request->getQueryParams();
  $limit = isset($queryParams['limit']) ? (int)$queryParams['limit'] : null;

  if ($limit) {
    $productos = array_slice($productos, 0, $limit);
  }

  return $renderer->render($response, 'productos/index.php', [
    'productos' => $productos
  ]);
});
