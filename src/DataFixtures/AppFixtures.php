<?php

namespace App\DataFixtures;

use App\Entity\Centre;
use App\Entity\Identifiant;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $centre = new Centre;
        $centre->setNom("Valrose");
        $centre->setTel("04 89 15 00 00");
        $centre->setAdresse("28 Av. Valrose");
        $centre->setZip("06000");
        $centre->setVille("Nice");
        $centre->setLatitude("43.716889");
        $centre->setLongitude("7.2664131");
        // $product = new Product();
        // $manager->persist($product);

        $manager->persist($centre);
        $manager->flush();
    }
}
