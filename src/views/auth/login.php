<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
</head>
<body>

<h1>Iniciar sesión</h1>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="<?= htmlspecialchars($basePath) ?>/auth/login">

    <div>
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
    </div>

    <div>
        <label>Contraseña:</label>
        <input type="password" name="password" required>
    </div>

    <button type="submit">
        Iniciar sesión
    </button>

</form>

<a href="<?= htmlspecialchars($basePath) ?>/auth/register">
    Crear una cuenta
</a>

</body>
</html>
