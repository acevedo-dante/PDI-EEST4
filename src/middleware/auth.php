use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Psr7\Response as SlimResponse;

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
