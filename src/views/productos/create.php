<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Crear Producto</title>
</head>
<body>

    <h1>Crear Producto</h1>

    <form method="POST" action="/productos">

        <label>Nombre:</label>
        <input type="text" name="nombre" required>

        <br><br>

        <label>Descripción:</label>
        <input type="text" name="descripcion">

        <br><br>

        <label>Precio:</label>
        <input type="number" name="precio">

        <br><br>

        <label>Stock:</label>
        <input type="number" name="stock">

        <br><br>

        <button type="submit">Crear producto</button>

    </form>

    <br>

    <a href="/productos/">Volver a productos</a>

</body>
</html>
