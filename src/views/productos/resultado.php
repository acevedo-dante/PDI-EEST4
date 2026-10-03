<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Producto recibido</title>
    <style>
        body { font-family: sans-serif; max-width: 500px; margin: 2rem auto; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 1rem 1.5rem; }
        .card p { margin: .6rem 0; }
    </style>
</head>
<body>

    <h1>Producto recibido</h1>

    <div class="card">
        <p><strong>Nombre:</strong> <?= htmlspecialchars($nombre) ?></p>

        <p><strong>Precio:</strong> $<?= htmlspecialchars($precio) ?></p>

        <p><strong>Descripción:</strong> <?= $descripcion !== '' ? htmlspecialchars($descripcion) : '—' ?></p>

        <?php if ($stock !== ''): ?>
            <p><strong>Stock:</strong> <?= htmlspecialchars($stock) ?></p>
        <?php endif; ?>
    </div>

    <br>

    <a href="<?= htmlspecialchars($basePath) ?>/productos/create">Crear otro producto</a>
    |
    <a href="<?= htmlspecialchars($basePath) ?>/productos/">Volver a productos</a>

</body>
</html>
