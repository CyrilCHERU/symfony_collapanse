<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class ApiLogoutHandler implements LogoutSuccessHandlerInterface
{
    public function onLogoutSuccess(\Symfony\Component\HttpFoundation\Request $request)
    {
        return new JsonResponse(['message' => "Déconnexion Réussie !"]);
    }
}
