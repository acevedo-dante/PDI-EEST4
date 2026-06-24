<h1>Listado de Productos</h1>

<ul>
    <?php foreach ($productos as $producto): ?>
        <li>
            <strong>ID:</strong> <?= $producto['id'] ?> <br>
            <strong>Nombre:</strong> <?= $producto['name'] ?> <br>
            <strong>Precio:</strong> $<?= $producto['price'] ?>
            <hr>
        </li>
    <?php endforeach; ?>
</ul>
