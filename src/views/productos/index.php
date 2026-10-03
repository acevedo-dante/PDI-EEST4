<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 2rem auto; }
        ul { list-style: none; padding: 0; }
        li { display: flex; justify-content: space-between; padding: .6rem 1rem; border: 1px solid #ddd; border-radius: 6px; margin-bottom: .5rem; }
        a { color: #1a56db; text-decoration: none; }
        .precio { font-weight: bold; }
    </style>
</head>
<body>

<h1>Listado de Productos</h1>

<p><a href="<?= htmlspecialchars($basePath) ?>/productos/create">+ Crear producto</a></p>

<?php if (empty($productos)): ?>
    <p>No hay productos para mostrar.</p>
<?php else: ?>
    <ul>
        <?php foreach ($productos as $producto): ?>
            <li>
                <a href="<?= htmlspecialchars($basePath) ?>/productos/<?= (int) $producto['id'] ?>">
                    <?= htmlspecialchars($producto['nombre']) ?>
                </a>
                <span class="precio">$<?= number_format((float) $producto['precio'], 0, ',', '.') ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

</body>
</html>
