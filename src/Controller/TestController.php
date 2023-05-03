<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/acceuil', name: 'app_test')]
    public function test(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/panier', name: 'app_test')]
    public function panier(): Response
    {
        return $this->render('test/panier.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/formulaires', name: 'app_test')]
    public function forms(): Response
    {
        return $this->render('test/forms.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/compte', name: 'app_test')]
    public function compte(): Response
    {
        return $this->render('test/compte.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/erreurs', name: 'app_test')]
    public function erreurs(): Response
    {
        return $this->render('test/erreurs.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

    #[Route('/formulaires', name: 'app_test')]
    public function formulaires(): Response
    {
        return $this->render('test/forms.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
