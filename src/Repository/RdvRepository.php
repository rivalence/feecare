<?php

namespace App\Repository;

use App\Entity\Rdv;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class RdvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rdv::class);
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

    public function getRdv(int $idRdv): mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT r
            FROM App\Entity\Rdv r
            WHERE r.idRdv = :id'
        )->setParameter('id', $idRdv);

        return $query->getResult();
    }

    public function getRdvsFamille(int $idUser): mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT r, u.nom, u.prenom, u.email
            FROM App\Entity\Rdv r, App\Entity\Users u
            WHERE u.idUser = r.educateurKey
            AND r.familleKey = :id
            ORDER BY r.dateRdv'
        )->setParameter('id', $idUser);

        return $query->getResult();
    }

    public function getRdvsEducateur(int $idUser): mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT r, u.nom, u.prenom, u.email
            FROM App\Entity\Rdv r, App\Entity\Users u
            WHERE r.familleKey = u.idUser
            AND r.educateurKey = :id
            ORDER BY r.dateRdv"
        )->setParameter('id', $idUser);

        return $query->getResult();
    }

    public function removeRdv(Rdv $rdv, ManagerRegistry $doctrine) : void
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($rdv);
        $entityManager->flush();
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
}