<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StripController extends AbstractController
{
    #[Route('/stripe', name: 'app_strip')]
    public function index(): Response
    {
        return $this->render('strip/index.html.twig', [
            'controller_name' => 'StripController',
        ]);
    }

    #[Route('/stripe/payment', name:'stripe_payment')]
    
    public function payment(){
    $stripeSecretKey = $this->getParameter('stripe_sk');
    \Stripe\Stripe::setApiKey($stripeSecretKey);

    try {

        $total = 1000; 

        $jsonStr = file_get_contents('php://input');
        $jsonObj = json_decode($jsonStr);
    
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $total,
            'currency' => 'eur',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);
    
        $output = [
            'paymentIntent' => $paymentIntent,
            'clientSecret' => $paymentIntent->client_secret,
        ];
    
        //echo json_encode($output);
        return new JsonResponse($output);
     } catch (\Error $e) {
       
        return new JsonResponse(['error' => $e->getMessage()], 500);

    }

    }

    

}
