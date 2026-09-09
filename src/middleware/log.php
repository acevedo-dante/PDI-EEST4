```php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as SlimResponse;

/**
 * Middleware de autenticación
 */
function authMiddleware(Request $request, RequestHandler $handler): Response
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    $userId = $_SESSION["user_id"] ?? null;

    if ($userId === null || $userId === "") {
        return new SlimResponse()
            ->withHeader("Location", "/auth/login")
            ->withStatus(302);
    }

    $request = $request->withAttribute("user_id", $userId);

    return $handler->handle($request);
}


/**
 * Middleware global de logging
 */
function logMiddleware(Request $request, RequestHandler $handler): Response
{
    // 1. Registrar el tiempo inicial ANTES de ejecutar la ruta.
    $start = microtime(true);

    // 2. Permitir que la ruta se ejecute y obtener su respuesta.
    $response = $handler->handle($request);

    // 3. Calcular el tiempo de ejecución en milisegundos.
    $executionTime = (microtime(true) - $start) * 1000;

    // 4. Obtener los datos necesarios.
    $date = date("Y-m-d H:i:s");
    $method = $request->getMethod();
    $path = $request->getUri()->getPath();
    $status = $response->getStatusCode();

    $logLine = sprintf(
        "[%s] %s %s - Status: %d - Tiempo: %.2f ms",
        $date,
        $method,
        $path,
        $status,
        $executionTime
    );

    // 5. Imprimir en consola.
    error_log($logLine);

    // 5. Escribir al final del archivo .log.
    file_put_contents(
        __DIR__ . "/app.log",
        $logLine . PHP_EOL,
        FILE_APPEND | LOCK_EX
    );

    // 6. Devolver la respuesta SIN MODIFICAR.
    return $response;
}
```
