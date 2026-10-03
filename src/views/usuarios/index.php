<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios</title>
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 2rem auto; }
        ul { list-style: none; padding: 0; }
        li { padding: .6rem 1rem; border: 1px solid #ddd; border-radius: 6px; margin-bottom: .5rem; }
        a { color: #1a56db; text-decoration: none; }
    </style>
</head>
<body>

<h1>Listado de Usuarios</h1>

<?php if (empty($usuarios)): ?>
    <p>No hay usuarios registrados.</p>
<?php else: ?>
    <ul>
        <?php foreach ($usuarios as $usuario): ?>
            <li>
                <a href="<?= htmlspecialchars($basePath) ?>/usuarios/<?= (int) $usuario['id'] ?>">
                    <?= htmlspecialchars((string) $usuario['nombre']) ?>
                </a>
                — <?= htmlspecialchars((string) $usuario['email']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

</body>
</html>
