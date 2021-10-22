<?php

namespace App\DataFixtures;

use App\Entity\Author;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuthorFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i=1; $i<=2; $i++) {
            $author = new Author();
            $author->setName("Author $i");
            $author->setBirthday(\DateTime::createFromFormat('Y-m-d', '1990-05-08'));
            $author->setNationality("Vietnam");
            $author->setImage("avatar.png");

            $manager->persist($author);
        }

        $manager->flush();
    }
}
