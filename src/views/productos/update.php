<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Producto</title>
</head>
<body>

    <h1>Editar Producto</h1>

    <form method="POST" action="/productos/<?php echo $producto['id']; ?>">

        <input type="hidden" name="_method" value="PUT">

        <label>Nombre:</label>
        <input
            type="text"
            name="nombre"
            value="<?php echo $producto['nombre']; ?>"
            required
        >

        <br><br>

        <label>Descripción:</label>
        <input
            type="text"
            name="descripcion"
            value="<?php echo $producto['descripcion']; ?>"
        >

        <br><br>

        <label>Precio:</label>
        <input
            type="number"
            name="precio"
            value="<?php echo $producto['precio']; ?>"
        >

        <br><br>

        <label>Stock:</label>
        <input
            type="number"
            name="stock"
            value="<?php echo $producto['stock']; ?>"
        >

        <br><br>

        <button type="submit">Actualizar producto</button>

    </form>

    <br>

    <a href="/productos/<?php echo $producto['id']; ?>">Volver</a>

</body>
</html>
