<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>

<h1>Crear cuenta</h1>

<?php if (!empty($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" action="<?= htmlspecialchars($basePath) ?>/auth/register">

    <div>
        <label>Nombre:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($old['name'] ?? '') ?>" required>
    </div>

    <div>
        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($old['email'] ?? '') ?>" required>
    </div>

    <div>
        <label>Contraseña:</label>
        <input type="password" name="password" minlength="8" required>
    </div>

    <button type="submit">
        Registrarse
    </button>

</form>

<a href="<?= htmlspecialchars($basePath) ?>/auth/login">
    Ya tengo una cuenta
</a>

</body>
</html>
