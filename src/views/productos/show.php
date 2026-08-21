<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Producto</title>
</head>
<body>

    <h1>Detalle del Producto</h1>

    <p>
        <strong>ID:</strong>
        <?php echo $producto['id']; ?>
    </p>

    <p>
        <strong>Nombre:</strong>
        <?php echo $producto['nombre']; ?>
    </p>

    <p>
        <strong>Descripción:</strong>
        <?php echo $producto['descripcion']; ?>
    </p>

    <p>
        <strong>Precio:</strong>
        $<?php echo $producto['precio']; ?>
    </p>

    <p>
        <strong>Stock:</strong>
        <?php echo $producto['stock']; ?>
    </p>

    <a href="/productos/">Volver</a>

    <br><br>

    <a href="/productos/update/<?php echo $producto['id']; ?>">
        Editar producto
    </a>

    <br><br>

    <form method="POST" action="/productos/<?php echo $producto['id']; ?>">
        <input type="hidden" name="_method" value="DELETE">
        <button type="submit">Eliminar producto</button>
    </form>

</body>
</html>
