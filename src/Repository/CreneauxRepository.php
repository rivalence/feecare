<?php

namespace App\Repository;

use App\Entity\Creneaux;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class CreneauxRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Creneaux::class);
    }

    public function getCurrentUserId(Users $user): mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u.idUser
            FROM App\Entity\Users u
            WHERE u.utilisateur = :pseudo
            '
        )->setParameter('pseudo', $user->getUserIdentifier());

        return $query->getResult();
    }

    public function saveCreneau(Creneaux $creneau, ManagerRegistry $doctrine) : string
    {
        try{
            $entityManager = $doctrine->getManager();
            $entityManager->persist($creneau);
            $entityManager->flush();
            return 'ok';
        } catch(Exception $e){
            return $e;
        }
    }

    public function fetchCreneaux(int $educateurkey): mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c
            FROM App\Entity\Creneaux c
            WHERE c.educateurKey = :educateurkey
            ORDER BY c.dateCreneau'
        )->setParameter('educateurkey', $educateurkey);

        return $query->getResult();
    }

    public function getCreneauToRemove(int $idCreneau) : mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c
            FROM App\Entity\Creneaux c
            WHERE c.idCreneau = :id'
        )->setParameter('id', $idCreneau);

        return $query->getResult();
    }

    public function removeCreneau(Creneaux $creneau, ManagerRegistry $doctrine): string
    {
        try{
            $entityManager = $doctrine->getManager();
            $entityManager->remove($creneau);
            $entityManager->flush();
            return 'ok';
        }catch(Exception $e){
            return $e;
        }
        
    }
}