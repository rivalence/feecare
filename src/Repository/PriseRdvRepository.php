<?php

namespace App\Repository;

use App\Entity\Creneaux;
use App\Entity\Rdv;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class PriseRdvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Users::class);
    }

    public function getCreneaux(int $educateurKey) : mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c
            FROM App\Entity\Creneaux c
            WHERE c.educateurKey = :educateurKey'
        )->setParameter('educateurKey', $educateurKey);

        return $query->getResult();
    }

    public function getCurrentUser(string $pseudo): mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u
            FROM App\Entity\Users u
            WHERE u.utilisateur = :pseudo
            '
        )->setParameter('pseudo', $pseudo);

        return $query->getResult();
    }

    public function findIdUser(string $nom): mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT user.idUser
            FROM App\Entity\Users user
            WHERE user.nom = :nom'
        )->setParameter('nom', $nom);

        return $query->getResult();
    }

    public function saveRdv(Rdv $rdv, ManagerRegistry $doctrine) : string
    {
        try{
           $entityManager = $doctrine->getManager();
            $entityManager->persist($rdv);
            $entityManager->flush(); 

            return 'ok';
        }catch (Exception $e){
            return $e;
        }
        
    }

    public function getTraitant(int $idTraitant) : mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u
            FROM App\Entity\Users u
            WHERE u.idUser = :id'
        )->setParameter('id', $idTraitant);

        return $query->getResult();
    }

    public function getCreneauChoisi(int $idCreneau) : mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c
            FROM App\Entity\Creneaux c
            WHERE c.idCreneau = :id'
        )->setParameter('id', $idCreneau);

        return $query->getResult();
    }

    public function removeCreneau(Creneaux $creneau, ManagerRegistry $doctrine) : void
    {
        $entityManager = $doctrine->getManager();

        $entityManager->remove($creneau);
        $entityManager->flush();
    }
}