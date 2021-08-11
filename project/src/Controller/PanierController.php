<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Book;
use App\Repository\BookRepository;
use App\Service\CartService;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier_index")
     */
    public function index(CartService $cartService): Response
    {
        $cart = $cartService->get();
        return $this->render('panier/index.html.twig', [
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/panier/ajouter/{id}", name="panier_add")
     */
    public function add(Book $book, CartService $cartService): Response
    {
        $cartService->add($book);
        return $this->redirectToRoute('panier_index');
    }
    
    /**
     * @Route("/panier/enlever/{id}", name="panier_enlever")
     */
    public function delete(Book $book, CartService $cartService): Response
    {
        $cartService->delete($book);
        return $this->redirectToRoute('panier_index');
    }

    /**
     * @Route("/panier/vider", name="panier_vider")
     */
    public function clear(CartService $cartService): Response
    {
        $cartService->clear();
        return $this->redirectToRoute('panier_index');
    }

    /**
     * @Route("/panier/supprimer/{id}", name="panier_supprimer")
     */
    public function removeLine(Book $book, CartService $cartService): Response
    {
        $cartService->removeLine($book);
        return $this->redirectToRoute('panier_index');
    }
}
