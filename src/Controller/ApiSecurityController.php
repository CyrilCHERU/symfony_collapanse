<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiSecurityController extends AbstractController
{
    /**
     * @Route("/api/login", name="api_security_login", methods={"POST"})
     *
     * @param SerializerInterface $serializer
     * @return void
     */
    public function login(SerializerInterface $serializer)
    {
        $json = $serializer->serialize(
            [
                'user' => $this->getUser(),
                'message' => "Merci de vous être connecté !"
            ],
            'json',
            [
                'groups' => ['users:login']
            ]
        );

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("/api/logout", name="api_security_logout", methods={"GET"})
     *
     * @return void
     */
    public function logout()
    { }
}
