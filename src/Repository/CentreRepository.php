<?php

namespace App\Repository;

use App\Entity\Centre;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class CentreRepository extends ServiceEntityRepository {
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Centre::class);
    }

    public function getCentres() : mixed 
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT c
            FROM App\Entity\Centre c'
            )->getResult();
        
        return $query;
    }
}