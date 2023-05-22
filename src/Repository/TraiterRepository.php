<?php

namespace App\Repository;

use App\Entity\Traiter;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class TraiterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Traiter::class);
    }

    public function findUserByName(string $name): mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u
            FROM App\Entity\Users u
            WHERE u.nom = :nom'
            )->setParameter('nom',$name);
        
            return $query->getResult();
    }

    public function addPatient(Traiter $traier, ManagerRegistry $doctrine): string
    {
        try{
           $entityManager = $doctrine->getManager(); 
           $entityManager->persist($traier);
           $entityManager->flush();

           return 'ok';
        }
        catch(Exception $e){
            return $e;
        }
    }

    public function fetchAllPatient(int $educateurId): mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u
            FROM App\Entity\Users u, App\Entity\Traiter t
            WHERE t.familleKey = u.idUser
            AND t.educateurKey = :id'
        )->setParameter('id', $educateurId);

        return $query->getResult();
    }

    public function getPatientToRemove(int $idPatient) : mixed
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery(
            'SELECT t
            FROM App\Entity\Traiter t
            WHERE t.familleKey = :id'
        )->setParameter('id', $idPatient);

        return $query->getResult();
    }

    public function removePatient(Traiter $traiter, ManagerRegistry $doctrine) : string
    {
        try{
            $entityManager = $doctrine->getManager();
            $entityManager->remove($traiter);
            $entityManager->flush();

            return 'ok';
        }catch(Exception $e){
            return $e;
        }
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

    public function fetchAllTraitant(int $familleId): mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u.nom, u.prenom
            FROM App\Entity\Users u, App\Entity\Traiter t
            WHERE t.educateurKey = u.idUser
            AND t.familleKey = :id'
        )->setParameter('id', $familleId);

        return $query->getResult();
    }
    public function fetchAllTraitantChat(int $familleId): mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u.idUser, u.nom, u.prenom
            FROM App\Entity\Users u, App\Entity\Traiter t
            WHERE t.educateurKey = u.idUser
            AND t.familleKey = :id'
        )->setParameter('id', $familleId);

        return $query->getResult();
    }
}