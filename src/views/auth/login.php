<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
</head>
<body>

<h1>Iniciar sesión</h1>

<form method="POST" action="/auth/login">

    <div>
        <label>Email:</label>
        <input type="email" name="email" required>
    </div>

    <div>
        <label>Contraseña:</label>
        <input type="password" name="password" required>
    </div>

    <button type="submit">
        Iniciar sesión
    </button>

</form>

<a href="/auth/register">
    Crear una cuenta
</a>

</body>
</html>
