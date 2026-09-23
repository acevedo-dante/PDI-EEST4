<?php

use Slim\App;

return function (App $app) {
    // Rutas del CRUD de productos
    $app->get('/productos/', function ($request, $response) {
        // Lógica para listar productos
        return $response;
    });

    $app->get('/productos/create', function ($request, $response) {
        // Formulario de creación
        return $response;
    });

    $app->get('/productos/{id}', function ($request, $response) {
        // Ver producto individual
        return $response;
    });

    $app->post('/productos', function ($request, $response) {
        // Guardar producto
        return $response;
    });

    $app->get('/productos/update/{id}', function ($request, $response) {
        // Formulario de edición
        return $response;
    });

    $app->put('/productos/{id}', function ($request, $response) {
        // Actualizar producto
        return $response;
    });

    $app->delete('/productos/{id}', function ($request, $response) {
        // Eliminar producto
        return $response;
    });
};
