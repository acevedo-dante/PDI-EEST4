<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Producto</title>
    <style>
        body { font-family: sans-serif; max-width: 500px; margin: 2rem auto; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 1rem 1.5rem; }
        .card p { margin: .6rem 0; }
    </style>
</head>
<body>

    <h1>Detalle del Producto</h1>

    <div class="card">
        <p><strong>ID:</strong> <?= (int) $producto['id'] ?></p>

        <p><strong>Nombre:</strong> <?= htmlspecialchars((string) $producto['nombre']) ?></p>

        <p><strong>Descripción:</strong> <?= htmlspecialchars((string) $producto['descripcion']) ?></p>

        <p><strong>Precio:</strong> $<?= htmlspecialchars((string) $producto['precio']) ?></p>

        <p><strong>Stock:</strong> <?= htmlspecialchars((string) $producto['stock']) ?></p>
    </div>

    <br>

    <a href="<?= htmlspecialchars($basePath) ?>/productos/">Volver</a>
    |
    <a href="<?= htmlspecialchars($basePath) ?>/productos/update/<?= (int) $producto['id'] ?>">Editar producto</a>

    <br><br>

    <form method="POST" action="<?= htmlspecialchars($basePath) ?>/productos/<?= (int) $producto['id'] ?>"
          onsubmit="return confirm('¿Eliminar este producto?');">
        <input type="hidden" name="_METHOD" value="DELETE">
        <button type="submit">Eliminar producto</button>
    </form>

</body>
</html>
