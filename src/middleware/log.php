<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Middleware global de logging.
 * Se registra en bootstrap.php con $app->add('logMiddleware').
 */
function logMiddleware(
    Request $request,
    RequestHandler $handler
): Response {

    // 1. Tiempo inicial, ANTES de ejecutar la ruta
    $start = microtime(true);

    // 2. Ejecutar la ruta y obtener su respuesta
    $response = $handler->handle($request);

    // 3. Tiempo de ejecución en milisegundos
    $executionTime = (microtime(true) - $start) * 1000;

    // 4. Línea de log: fecha, método, ruta, estado y tiempo
    $logLine = sprintf(
        '[%s] %s %s - Status: %d - Tiempo: %.2f ms',
        date('Y-m-d H:i:s'),
        $request->getMethod(),
        $request->getUri()->getPath(),
        $response->getStatusCode(),
        $executionTime
    );

    // 5. Consola (stderr del servidor) y final del archivo .log
    error_log($logLine);
    file_put_contents(
        __DIR__ . '/../../app.log',
        $logLine . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );

    // 6. La respuesta se devuelve sin modificar
    return $response;
}
