<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>
<body>

<h1>Crear cuenta</h1>

<form method="POST" action="/auth/register">

    <div>
        <label>Nombre:</label>
        <input type="text" name="name" required>
    </div>

    <div>
        <label>Email:</label>
        <input type="email" name="email" required>
    </div>

    <div>
        <label>Contraseña:</label>
        <input type="password" name="password" required>
    </div>

    <button type="submit">
        Registrarse
    </button>

</form>

<a href="/auth/login">
    Ya tengo una cuenta
</a>

</body>
</html>
