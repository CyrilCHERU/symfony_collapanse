<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/admin", name="home")
     */
    public function home(UserInterface $user)
    {

        return $this->render('home/index.html.twig', [
            'user' => $user
        ]);
    }
}
