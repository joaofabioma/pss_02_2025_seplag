<?php

namespace PSS0225Seplag\Controllers;

use PSS0225Seplag\Config\JwtConfig;
use PSS0225Seplag\Services\AuthService;
use PSS0225Seplag\Services\JWTService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AuthController
{
    private $authService;
    private $jwtService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->jwtService = new JWTService;
    }

    public function renewToken(Request $request)
    {
        $authHeader = $request->headers->get('Authorization');
        if (empty($authHeader)) {
            return new JsonResponse(['error' => 'Authorization header missing'], 401);
        }

        $token = str_replace('Bearer ', '', $authHeader);
        
        if (!$this->jwtService->canRenewToken($token)) {
            return new JsonResponse(['error' => 'Token cannot be renewed at this time'], 400);
        }

        $newToken = $this->jwtService->renewToken($token);
        
        if (!$newToken) {
            return new JsonResponse(['error' => 'Token renewal failed'], 400);
        }

        return new JsonResponse([
            'token' => $newToken,
            'expires_in' => JwtConfig::getConfig()['expiration']
        ]);
    }
}