<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/auth')]
class AuthController extends AbstractController
{
    /**
     * Login endpoint for JWT authentication.
     *
     * Expects JSON body:
     * {
     *   "username": "customer-uuid-here",
     *   "password": "customer-password-here"
     * }
     *
     * Returns JWT token on success.
     * This route is handled by json_login firewall in security.yaml.
     *
     * @return JsonResponse
     */
    #[Route('/login', name: 'app_auth_login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        // This method is never reached - handled by json_login firewall
        return new JsonResponse([
            'error' => 'Invalid credentials'
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }
}