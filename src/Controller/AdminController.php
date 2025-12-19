<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\ContactMessage;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\CategoryType;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('', name: 'app_admin_dashboard')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        $productCount = $em->getRepository(Product::class)->count([]);
        $categoryCount = $em->getRepository(Category::class)->count([]);
        $orderCount = $em->getRepository(Order::class)->count([]);
        $contactCount = $em->getRepository(ContactMessage::class)->count([]);

        return $this->render('admin/dashboard.html.twig', [
            'productCount' => $productCount,
            'categoryCount' => $categoryCount,
            'orderCount' => $orderCount,
            'contactCount' => $contactCount,
        ]);
    }

    // === PRODUCTS CRUD ===
    #[Route('/products', name: 'app_admin_products')]
    public function listProducts(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findAll();

        return $this->render('admin/products/list.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/products/{id}', name: 'app_admin_product_show')]
    public function showProduct(Product $product): Response
    {
        return $this->render('admin/products/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/products/{id}/edit', name: 'app_admin_product_edit', methods: ['GET', 'POST'])]
    public function editProduct(Request $request, Product $product, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Produit mis à jour avec succès.');

            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('admin/products/edit.html.twig', [
            'form' => $form,
            'product' => $product,
        ]);
    }

    #[Route('/products/{id}/delete', name: 'app_admin_product_delete', methods: ['POST'])]
    public function deleteProduct(Request $request, Product $product, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($product);
            $em->flush();
            $this->addFlash('success', 'Produit supprimé avec succès.');
        }

        return $this->redirectToRoute('app_admin_products');
    }

    // === CATEGORIES CRUD ===
    #[Route('/categories', name: 'app_admin_categories')]
    public function listCategories(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findAll();

        return $this->render('admin/categories/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categories/{id}/edit', name: 'app_admin_category_edit', methods: ['GET', 'POST'])]
    public function editCategory(Request $request, Category $category, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Catégorie mise à jour avec succès.');

            return $this->redirectToRoute('app_admin_categories');
        }

        return $this->render('admin/categories/edit.html.twig', [
            'form' => $form,
            'category' => $category,
        ]);
    }

    #[Route('/categories/{id}/delete', name: 'app_admin_category_delete', methods: ['POST'])]
    public function deleteCategory(Request $request, Category $category, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($category);
            $em->flush();
            $this->addFlash('success', 'Catégorie supprimée avec succès.');
        }

        return $this->redirectToRoute('app_admin_categories');
    }

    // === ORDERS CRUD ===
    #[Route('/orders', name: 'app_admin_orders')]
    public function listOrders(EntityManagerInterface $em): Response
    {
        $orders = $em->getRepository(Order::class)->findAll();

        return $this->render('admin/orders/list.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/orders/{id}', name: 'app_admin_order_show')]
    public function showOrder(Order $order): Response
    {
        return $this->render('admin/orders/show.html.twig', [
            'order' => $order,
        ]);
    }

    // === MESSAGES CRUD ===
    #[Route('/messages', name: 'app_admin_messages')]
    public function listMessages(EntityManagerInterface $em): Response
    {
        $messages = $em->getRepository(ContactMessage::class)->findAll();

        return $this->render('admin/messages/list.html.twig', [
            'messages' => $messages,
        ]);
    }

    #[Route('/messages/{id}', name: 'app_admin_message_show')]
    public function showMessage(ContactMessage $message): Response
    {
        return $this->render('admin/messages/show.html.twig', [
            'message' => $message,
        ]);
    }

    #[Route('/messages/{id}/delete', name: 'app_admin_message_delete', methods: ['POST'])]
    public function deleteMessage(Request $request, ContactMessage $message, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete' . $message->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($message);
            $em->flush();
            $this->addFlash('success', 'Message supprimé avec succès.');
        }

        return $this->redirectToRoute('app_admin_messages');
    }
}
