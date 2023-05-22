<?php

namespace App\DataFixtures;

use App\Entity\Creneaux;
use App\Entity\Educateur;
use App\Entity\Users;
use App\Entity\Identifiant;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $identifiant = new Identifiant;
        $identifiant->setLibelle("Ajunior");
        // $product = new Product();
        // $manager->persist($product);

        $manager->persist($identifiant);
        $manager->flush();
    }
}
