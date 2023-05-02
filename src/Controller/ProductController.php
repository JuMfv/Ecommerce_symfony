<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\Product1Type;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;



#[Route('/product')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'app_product_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em,ProductRepository $productRepository, TranslatorInterface $translator): Response
    {
        return $this->render('product/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
        
           // $em->persist($product);
            $em->flush();
            $this->addFlash('success', $translator->trans('product.added'));
    }

    #[Route('/new', name: 'app_product_new', methods: ['GET', 'POST'])]
    public function new(EntityManagerInterface $em, Request $request, ProductRepository $productRepository, TranslatorInterface $translator): Response
    {
        $product = new Product();
        $form = $this->createForm(Product1Type::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $imageFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {

                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('upload_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    $this->addFlash('warning', "Impossible d'ajouter l'image");
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $product->setImage($newFilename);
            }

            $em->persist($product);
            $em->flush();
            $this->addFlash('success', $translator->trans('product.added'));

            $productRepository->save($product, true);

            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
        }

        

        return $this->renderForm('product/new.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);


    }

    #[Route('/{id}', name: 'app_product_show', methods: ['GET'])]
    public function show(Product $product): Response
    {
        if($product == null){
            $this->addFlash('danger', 'Produit introuvable');
            return $this->redirectToRoute('app_product');
        }


        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);

    }

    #[Route('/{id}/edit', name: 'app_product_edit', methods: ['GET', 'POST'])]
    public function edit(EntityManagerInterface $em,Request $request, Product $product, ProductRepository $productRepository): Response
    {
        $form = $this->createForm(Product1Type::class, $product);
        $form->handleRequest($request);

        

        if($form->isSubmitted() && $form->isValid()){
            $em->persist($product);
            $em->flush();
            $this->addFlash('success', 'Produit modifié');
            return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('product/edit.html.twig', [
            'product' => $product,
            'form' => $form,
        ]);

        

    }

   /* #[Route('/{id}', name: 'app_product_delete', methods: ['POST'])]
    public function delete(EntityManagerInterface $em,Request $request, Product $product, ProductRepository $productRepository): Response
    {
        if($product == null){
            $this->addFlash('danger', 'Produit introuvable');
        }
        else{
            $em->remove($product);
            $em->flush();
            $this->addFlash('warning', 'Produit supprimé');
        }

        return $this->redirectToRoute('app_product_index', [], Response::HTTP_SEE_OTHER);
    }*/

    #[Route('/delete/{id}', name:'delete_product')]
    public function delete(Product $product = null, EntityManagerInterface $em){
        if($product == null){
            $this->addFlash('danger', 'Produit introuvable');
        }
        else{
            $em->remove($product);
            $em->flush();
            $this->addFlash('warning', 'Produit supprimé');
        }
        return $this->redirectToRoute('app_product_index');
    }

    

}
