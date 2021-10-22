<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CartFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $cart = new Cart();
        $cart->setAmount(10);
        $manager->persist($cart);

        $manager->flush();
    }
}
