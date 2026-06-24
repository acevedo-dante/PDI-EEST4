<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos</title>
</head>
<body>

  <h1>Lista de Productos</h1>

  <ul>
    <?php foreach ($productos as $producto) { ?>
      <li>
        <a href="/productos/<?php echo $producto['id']; ?>">
          <?php echo $producto['name']; ?>
        </a>
        - $<?php echo $producto['price']; ?>
      </li>
    <?php } ?>
  </ul>

</body>
</html>
