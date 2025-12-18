<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart')]
final class CartController extends AbstractController
{
    #[Route(name: 'app_cart', methods: ['GET'])]
    public function index(CartService $cartService): Response
    {
        return $this->render('cart/index.html.twig', [
            'items' => $cartService->getCart(),
            'total' => $cartService->getTotal(),
        ]);
    }

    #[Route('/add/{id}', name: 'app_cart_add', methods: ['GET'])]
    public function add(int $id, CartService $cartService): Response
    {
        $cartService->add($id);

        $this->addFlash('success', 'Produit ajouté au panier.');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/remove/{id}', name: 'app_cart_remove', methods: ['GET'])]
    public function remove(int $id, CartService $cartService): Response
    {
        $cartService->remove($id);

        $this->addFlash('success', 'Produit retiré du panier.');

        return $this->redirectToRoute('app_cart');
    }

    #[Route('/clear', name: 'app_cart_clear', methods: ['GET'])]
    public function clear(CartService $cartService): Response
    {
        $cartService->clear();

        $this->addFlash('success', 'Panier vidé.');

        return $this->redirectToRoute('app_cart');
    }
}