<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\InputBag;

use Symfony\Component\Routing\Annotation\Route;

class LookupController extends AbstractController
{
    
    #[Route('/lookup', name: 'app_lookup')]
    
    public function index(Request $request, ProductRepository $productRepository): Response
    {

        $search = $request->query->get('search');
        $products = $productRepository->findBySearch($search);
        return $this->render('lookup/index.html.twig', [
            'controller_name' => 'LookupController',
            'products' => $products

        ]);
    }
}
