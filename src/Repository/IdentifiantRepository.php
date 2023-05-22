<?php

namespace App\Repository;

use App\Entity\Identifiant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class IdentifiantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Identifiant::class);
    }

    public function findId(string $id): mixed      //Vérifier si l'id entré par le User est bien en BDD
    {
        $entityManager = $this->getEntityManager();  

        $query = $entityManager->createQuery(
            'SELECT id.libelle
            FROM App\Entity\Identifiant id
            WHERE id.libelle = :id'
        )->setParameter('id', $id);

        return $query->getResult();
    }

    public function findIdInUser(string $id): mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT user.utilisateur
            FROM App\Entity\Users user
            WHERE user.utilisateur = :id'
        )->setParameter('id', $id);

        return $query->getResult();
    }

    public function addIdentifiant(Identifiant $identifiant, ManagerRegistry $doctrine)
    {
        $em = $doctrine->getManager();

        $em->persist($identifiant);
        $em->flush($identifiant);
    }
}