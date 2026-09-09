<?php

use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/database/database.php';

Dotenv::createImmutable(__DIR__ . '/..')->safeLoad();

$app = AppFactory::create();

require __DIR__ . '/middleware/auth.php';

$app->add(logMiddleware);

$renderer = new PhpRenderer(__DIR__ . '/views');

$database = new Database();
$pdo = $database->getConnection();

// ==========================================
// AUTENTICACIÓN
// ==========================================

// GET /auth/register
$app->get('/auth/register', function ($request, $response) use ($renderer) {

    return $renderer->render(
        $response,
        'auth/register.php'
    );
});


// POST /auth/register
$app->post('/auth/register', function ($request, $response) use ($pdo) {

    $data = $request->getParsedBody();

    $name = trim($data['name'] ?? '');
    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';

    if ($name === '' || $email === '' || $password === '') {
        $response->getBody()->write(
            'Todos los campos son obligatorios.'
        );

        return $response->withStatus(400);
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response->getBody()->write(
            'El email no es válido.'
        );

        return $response->withStatus(400);
    }

    $stmt = $pdo->prepare(
        'SELECT id FROM users WHERE email = ?'
    );

    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        $response->getBody()->write(
            'El email ya está registrado.'
        );

        return $response->withStatus(409);
    }

    $passwordHash = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $stmt = $pdo->prepare(
        'INSERT INTO users (name, email, password)
         VALUES (?, ?, ?)'
    );

    $stmt->execute([
        $name,
        $email,
        $passwordHash
    ]);

    return $response
        ->withHeader('Location', '/auth/login')
        ->withStatus(302);
});


// GET /auth/login
$app->get('/auth/login', function ($request, $response) use ($renderer) {

    return $renderer->render(
        $response,
        'auth/login.php'
    );
});


// POST /auth/login
$app->post('/auth/login', function ($request, $response) use ($pdo) {

    $data = $request->getParsedBody();

    $email = trim($data['email'] ?? '');
    $password = $data['password'] ?? '';

    if ($email === '' || $password === '') {

        $response->getBody()->write(
            'Email y contraseña son obligatorios.'
        );

        return $response->withStatus(400);
    }

    $stmt = $pdo->prepare(
        'SELECT id, name, email, password
         FROM users
         WHERE email = ?'
    );

    $stmt->execute([$email]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (
        !$user ||
        !password_verify($password, $user['password'])
    ) {

        $response->getBody()->write(
            'Email o contraseña incorrectos.'
        );

        return $response->withStatus(401);
    }

    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_email'] = $user['email'];

    return $response
        ->withHeader('Location', '/productos/')
        ->withStatus(302);
});


// ==========================================
// PRODUCTOS
// ==========================================

// GET /
$app->get('/', function ($request, $response) use ($renderer) {
    return $renderer->render($response, 'index.php');
});

// GET /
// Página principal
$app->get('/', function ($request, $response) use ($renderer) {
    return $renderer->render($response, 'index.php');
});


// ==========================================
// RUTAS PROTEGIDAS
// ==========================================


// GET /productos/
// Lista todos los productos
$app->get('/productos/', function ($request, $response) use ($renderer, $pdo) {

    $stmt = $pdo->query('SELECT * FROM productos');
    $productos = $stmt->fetchAll();

    return $renderer->render($response, 'productos/index.php', [
        'productos' => $productos
    ]);

})->add(authMiddleware);


// GET /productos/create
// Muestra el formulario para crear
$app->get('/productos/create', function ($request, $response) use ($renderer) {

    return $renderer->render($response, 'productos/create.php');

})->add(authMiddleware);


// GET /productos/update/{id}
// Muestra el formulario para editar
$app->get('/productos/update/{id}', function ($request, $response, $args) use ($renderer, $pdo) {

    $id = (int) $args['id'];

    $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = ?');
    $stmt->execute([$id]);

    $producto = $stmt->fetch();

    if (!$producto) {
        return $renderer->render($response, 'productos/not_found.php');
    }

    return $renderer->render($response, 'productos/update.php', [
        'producto' => $producto
    ]);

})->add(authMiddleware);


// GET /productos/{id}
// Muestra un producto
$app->get('/productos/{id}', function ($request, $response, $args) use ($renderer, $pdo) {

    $id = (int) $args['id'];

    $stmt = $pdo->prepare('SELECT * FROM productos WHERE id = ?');
    $stmt->execute([$id]);

    $producto = $stmt->fetch();

    if (!$producto) {
        return $renderer->render($response, 'productos/not_found.php');
    }

    return $renderer->render($response, 'productos/show.php', [
        'producto' => $producto
    ]);

})->add(authMiddleware);


// POST /productos
// Crea un producto
$app->post('/productos', function ($request, $response) use ($pdo) {

    $data = $request->getParsedBody();

    $nombre = $data['nombre'] ?? '';
    $precio = $data['precio'] ?? 0;
    $descripcion = $data['descripcion'] ?? '';
    $stock = $data['stock'] ?? 0;

    try {

        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'INSERT INTO productos (nombre, descripcion, precio, stock)
             VALUES (?, ?, ?, ?)'
        );

        $stmt->execute([
            $nombre,
            $descripcion,
            $precio,
            $stock
        ]);

        $pdo->commit();

        return $response
            ->withHeader('Location', '/productos/')
            ->withStatus(302);

    } catch (Exception $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }

})->add(authMiddleware);


// PUT /productos/{id}
// Actualiza un producto
$app->put('/productos/{id}', function ($request, $response, $args) use ($pdo) {

    $id = (int) $args['id'];

    $data = $request->getParsedBody();

    $nombre = $data['nombre'] ?? '';
    $precio = $data['precio'] ?? 0;
    $descripcion = $data['descripcion'] ?? '';
    $stock = $data['stock'] ?? 0;

    try {

        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'UPDATE productos
             SET nombre = ?, descripcion = ?, precio = ?, stock = ?
             WHERE id = ?'
        );

        $stmt->execute([
            $nombre,
            $descripcion,
            $precio,
            $stock,
            $id
        ]);

        $pdo->commit();

        return $response
            ->withHeader('Location', '/productos/' . $id)
            ->withStatus(302);

    } catch (Exception $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }

})->add(authMiddleware);


// DELETE /productos/{id}
// Elimina un producto
$app->delete('/productos/{id}', function ($request, $response, $args) use ($pdo) {

    $id = (int) $args['id'];

    try {

        $pdo->beginTransaction();

        $stmt = $pdo->prepare(
            'DELETE FROM productos WHERE id = ?'
        );

        $stmt->execute([$id]);

        $pdo->commit();

        return $response->withStatus(204);

    } catch (Exception $e) {

        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }

})->add(authMiddleware);
});


return $app;
