<?php

namespace App\DataFixtures;

use App\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BookFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i<=2; $i++) {
            $book = new Book();
            $book->setTitle("Book $i");
            $book->setPublisher("Havard");
            $book->setPrice(23.45);
            $book->setQuantity(rand(10, 30));
            $book->setImage("cover.jpg");

            $manager->persist($book);
        }

        $manager->flush();
    }
}
