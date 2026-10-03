<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
    <style>
        body { font-family: sans-serif; max-width: 500px; margin: 2rem auto; }
        label { display: block; margin-top: 1rem; font-weight: bold; }
        input { width: 100%; padding: .5rem; box-sizing: border-box; }
        button { margin-top: 1.5rem; padding: .6rem 1.2rem; }
        .error { color: #b00020; }
    </style>
</head>
<body>

    <h1>Crear Producto</h1>

    <?php if (!empty($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="<?= htmlspecialchars($basePath) ?>/productos">

        <label>Nombre:</label>
        <input type="text" name="nombre" value="<?= htmlspecialchars($old['nombre'] ?? '') ?>" required>

        <label>Descripción:</label>
        <input type="text" name="descripcion" value="<?= htmlspecialchars($old['descripcion'] ?? '') ?>">

        <label>Precio:</label>
        <input type="number" name="precio" min="0" step="any" value="<?= htmlspecialchars($old['precio'] ?? '') ?>" required>

        <label>Stock:</label>
        <input type="number" name="stock" min="0" step="1" value="<?= htmlspecialchars($old['stock'] ?? '') ?>">

        <button type="submit">Crear producto</button>

    </form>

    <br>

    <a href="<?= htmlspecialchars($basePath) ?>/productos/">Volver a productos</a>

</body>
</html>
