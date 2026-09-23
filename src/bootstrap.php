<?php

use Slim\App;
use Slim\Views\PhpRenderer;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

/** @var App $app */
/** @var PhpRenderer $renderer */
/** @var Database $database */

require __DIR__ . '/routes/auth.routes.php';
require __DIR__ . '/routes/usuarios.routes.php';
require __DIR__ . '/routes/productos.routes.php';
