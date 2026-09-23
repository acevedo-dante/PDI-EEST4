<?php

use Slim\App;

return function (App $app) {
    // Rutas de usuarios
    $app->get('/usuarios', function ($request, $response) {
        // Tu lógica de usuarios aquí
        return $response;
    });
};
