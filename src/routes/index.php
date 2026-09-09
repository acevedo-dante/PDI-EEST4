```php
<?php

use Slim\Factory\AppFactory;

require __DIR__ . "/../vendor/autoload.php";

$app = AppFactory::create();

// Middleware global de logging.
// Se ejecutará para todas las peticiones.
$app->add(logMiddleware);

// Rutas...
```
