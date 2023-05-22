<?php

namespace App\Repository;

use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class RegisterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function save(Users $user, ManagerRegistry $doctrine): int
    {
        try{
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return 1;
        } catch(Exception $e){
            return 0;
        }
    }

    public function findUserByPseudo(string $pseudo): mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u.utilisateur
            FROM App\ENtity\Users u
            WHERE u.utilisateur = :pseudo'
            )->setParameter('pseudo', $pseudo);

        return $query->getResult();
    }
}