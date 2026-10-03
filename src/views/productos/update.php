<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
    <style>
        body { font-family: sans-serif; max-width: 500px; margin: 2rem auto; }
        label { display: block; margin-top: 1rem; font-weight: bold; }
        input { width: 100%; padding: .5rem; box-sizing: border-box; }
        button { margin-top: 1.5rem; padding: .6rem 1.2rem; }
        .error { color: #b00020; }
    </style>
</head>
<body>

    <h1>Editar Producto</h1>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= htmlspecialchars($basePath) ?>/productos/<?= (int) $producto['id'] ?>">

        <input type="hidden" name="_METHOD" value="PUT">

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars((string) $producto['nombre']) ?>" required>

        <label>Descripción:</label>
        <input type="text" name="descripcion" value="<?= htmlspecialchars((string) $producto['descripcion']) ?>">

        <label>Precio:</label>
        <input type="number" name="precio" min="0" step="1" value="<?= htmlspecialchars((string) $producto['precio']) ?>" required>

        <label>Stock:</label>
        <input type="number" name="stock" min="0" step="1" value="<?= htmlspecialchars((string) $producto['stock']) ?>">

        <button type="submit">Actualizar producto</button>

    </form>

    <br>

    <a href="<?= htmlspecialchars($basePath) ?>/productos/<?= (int) $producto['id'] ?>">Volver</a>

</body>
</html>
