<?php

namespace App\Security;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiAuthenticator extends AbstractGuardAuthenticator
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    public function supports(Request $request)
    {
        // todo = > prise de la main sur le controller
        return $request->getPathInfo() === "/api/login" && $request->getMethod() === "POST";
    }

    public function getCredentials(Request $request)
    {
        // todo
        $json = $request->getContent(); // == {"email": "user0@gmail.com", "password": "password"}

        return json_decode($json, true);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        // todo
        if (empty($credentials['email'])) {
            throw new AuthenticationException("Vous devez fournir un email");
        }

        try {
            return $userProvider->loadUserByUsername($credentials['email']);
        } catch (Exception $e) {
            throw new AuthenticationException("Adresse email ou mot de passe erroné");
        }
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // todo
        // dd($user);
        if (!$this->encoder->isPasswordValid($user, $credentials['password'])) {
            throw new AuthenticationException("Adresse email ou mot de passe erroné");
        }
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // todo
        //dd("oups");
        return new JsonResponse([
            'message' => $exception->getMessage()
        ], 403);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // todo
        // dans tous les cas on arrivera à login() et dans tous les cas on arrivera a la fonction login()
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // todo
    }

    public function supportsRememberMe()
    {
        // todo
    }
}
