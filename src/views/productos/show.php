<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle Producto</title>
</head>
<body>

  <h1>Detalle del Producto</h1>

  <?php if ($producto) { ?>

    <p><strong>ID:</strong> <?php echo $producto['id']; ?></p>
    <p><strong>Nombre:</strong> <?php echo $producto['name']; ?></p>
    <p><strong>Precio:</strong> $<?php echo $producto['price']; ?></p>

    <a href="/productos">Volver</a>

  <?php } else { ?>

    <p>Producto no encontrado</p>
    <a href="/productos">Volver</a>

  <?php } ?>

</body>
</html>
