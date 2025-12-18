<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer des catégories
        $categories = [];
        $categoryNames = ['Colliers', 'Boucles d\'oreilles', 'Bracelets', 'Bagues'];

        foreach ($categoryNames as $name) {
            $category = new Category();
            $category->setName($name);
            $category->setText('Collection élégante de ' . strtolower($name) . ' en or et diamants.');
            $manager->persist($category);
            $categories[] = $category;
        }

        // Créer des produits
        $products = [
            ['Collier Diamant', 'Collier élégant avec diamants', 2500.00, 10, 'Or 18k', 0, 'https://images.unsplash.com/photo-1515372039744-b8f02a3ae446?w=400&h=300&fit=crop'],
            ['Boucles Perles', 'Boucles d\'oreilles en perles fines', 800.00, 15, 'Perles', 1, 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=400&h=300&fit=crop'],
            ['Bracelet Or', 'Bracelet en or massif', 1200.00, 8, 'Or 18k', 2, 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=400&h=300&fit=crop'],
            ['Bague Saphir', 'Bague avec saphir central', 1800.00, 5, 'Or blanc', 3, 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=400&h=300&fit=crop'],
            ['Collier Perles', 'Collier de perles naturelles', 950.00, 12, 'Perles', 0, 'https://images.unsplash.com/photo-1596944924616-7b38e7cfac36?w=400&h=300&fit=crop'],
            ['Boucles Or', 'Boucles d\'oreilles en or', 600.00, 20, 'Or 18k', 1, 'https://images.unsplash.com/photo-1617038260897-41a1f14a8ca0?w=400&h=300&fit=crop'],
        ];

        foreach ($products as $prod) {
            $product = new Product();
            $product->setName($prod[0]);
            $product->setDescription($prod[1]);
            $product->setPrice($prod[2]);
            $product->setStock($prod[3]);
            $product->setMaterial($prod[4]);
            $product->setCategory($categories[$prod[5]]);
            $product->setImage($prod[6]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
