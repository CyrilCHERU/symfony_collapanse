<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CareController extends AbstractController
{
    /**
     * @Route("/care", name="care")
     */
    public function index()
    {
        return $this->render('care/index.html.twig', [
            'controller_name' => 'CareController',
        ]);
    }
}
