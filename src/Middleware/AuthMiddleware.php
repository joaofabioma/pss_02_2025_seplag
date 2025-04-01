<?php

namespace PSS0225Seplag\Middleware;

use PSS0225Seplag\Services\AuthService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


class AuthMiddleware
{
    private $authService;

    public function __construct($pdo)
    {
        $this->authService = new AuthService($pdo);
    }

    ////public function __invoke(Request $request, Response $handler, callable $next)
    public function __invoke($request, callable $handler)
    {
        $authHeader = $request->headers->get('Authorization');
        if (empty($authHeader) || !isset($authHeader[0])) {
            $response = new JsonResponse(['error' => 'Use Authorization'], Response::HTTP_UNAUTHORIZED);
            $response->send();
            return $response;
        }

        $a = !empty($authHeader[0]) && strlen($authHeader[0]) < 10 ? $authHeader : $authHeader[0];
        $token = str_replace('Bearer ', '', $a);
        $payload = $this->authService->validateToken($token);

        if (!$payload) {
            error_log('Token inválido ou expirado');
            $response = new JsonResponse(['error' => 'Token Inválido ou Expirado'], Response::HTTP_UNAUTHORIZED);
            $response->send();
            return $response;
        }

        $request->attributes->set('user', $payload);

        $response = $handler($request);

        if ($response === null) {
            error_log('Handler did not return a response');
            return new JsonResponse(['error' => 'Handler did not return a response'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        $response->setContent(json_encode([
            'message' => 'Access granted',
            'user' => $payload
        ]));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    public function hasRole($role)
    {
        return function ($request, $handler) use ($role) {
            $user = $request->attributes->get('user');
            if ($user['role'] !== $role) {
                throw new \Exception("Unauthorized access");
            }
            return $handler->handle($request);
        };
    }
}
