<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\Wishlist;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
final class WishlistController extends AbstractController
{
    #[Route('/wishlist', name: 'app_wishlist')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $wishlists = $entityManager->getRepository(Wishlist::class)->findBy(['user' => $user]);

        return $this->render('wishlist/index.html.twig', [
            'wishlists' => $wishlists,
        ]);
    }

    #[Route('/wishlist/add/{id}', name: 'app_wishlist_add')]
    public function add(Product $product, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        // Check if already in wishlist
        $existing = $entityManager->getRepository(Wishlist::class)->findOneBy([
            'user' => $user,
            'product' => $product
        ]);

        if (!$existing) {
            $wishlist = new Wishlist();
            $wishlist->setUser($user);
            $wishlist->setProduct($product);

            $entityManager->persist($wishlist);
            $entityManager->flush();

            $this->addFlash('success', 'Produit ajouté à votre wishlist.');
        } else {
            $this->addFlash('info', 'Ce produit est déjà dans votre wishlist.');
        }

        return $this->redirectToRoute('app_product_show', ['id' => $product->getId()]);
    }

    #[Route('/wishlist/remove/{id}', name: 'app_wishlist_remove')]
    public function remove(Wishlist $wishlist, EntityManagerInterface $entityManager): Response
    {
        // Ensure the wishlist belongs to the current user
        if ($wishlist->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $entityManager->remove($wishlist);
        $entityManager->flush();

        $this->addFlash('success', 'Produit retiré de votre wishlist.');

        return $this->redirectToRoute('app_wishlist');
    }
}
