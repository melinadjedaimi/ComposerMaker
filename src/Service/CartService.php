<?php

namespace App\Service;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private SessionInterface $session;
    private ProductRepository $productRepository;

    public function __construct(RequestStack $requestStack, ProductRepository $productRepository)
    {
        $this->session = $requestStack->getSession();
        $this->productRepository = $productRepository;
    }

    public function add(int $productId, int $quantity = 1): void
    {
        $cart = $this->session->get('cart', []);

        if (!isset($cart[$productId])) {
            $cart[$productId] = 0;
        }

        $cart[$productId] += $quantity;

        $this->session->set('cart', $cart);
    }

    public function remove(int $productId): void
    {
        $cart = $this->session->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        $this->session->set('cart', $cart);
    }

    public function update(int $productId, int $quantity): void
    {
        $cart = $this->session->get('cart', []);

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
        }

        $this->session->set('cart', $cart);
    }

    public function getCart(): array
    {
        $cart = $this->session->get('cart', []);
        $cartWithData = [];

        foreach ($cart as $id => $quantity) {
            $product = $this->productRepository->find($id);
            if ($product) {
                $cartWithData[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            }
        }

        return $cartWithData;
    }

    public function getTotal(): float
    {
        $total = 0;

        foreach ($this->getCart() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        return $total;
    }

    public function getItemCount(): int
    {
        $cart = $this->session->get('cart', []);
        return array_sum($cart);
    }

    public function clear(): void
    {
        $this->session->remove('cart');
    }
}