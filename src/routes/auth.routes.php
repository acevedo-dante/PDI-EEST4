<?php

use Slim\App;

return function (App $app) {
    // Rutas de autenticación
    $app->get('/login', function ($request, $response) {
        // Tu lógica de login aquí
        return $response;
    });
};
