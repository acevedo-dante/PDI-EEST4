<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Usuario</title>
    <style>
        body { font-family: sans-serif; max-width: 500px; margin: 2rem auto; }
        .card { border: 1px solid #ddd; border-radius: 8px; padding: 1rem 1.5rem; }
        .card p { margin: .6rem 0; }
    </style>
</head>
<body>

    <h1>Detalle del Usuario</h1>

    <div class="card">
        <p><strong>ID:</strong> <?= (int) $usuario['id'] ?></p>
        <p><strong>Nombre:</strong> <?= htmlspecialchars((string) $usuario['nombre']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars((string) $usuario['email']) ?></p>
    </div>

    <br>

    <a href="<?= htmlspecialchars($basePath) ?>/usuarios/">Volver</a>

</body>
</html>
