<?php

namespace App\Repository;

use App\Entity\Commentaire;
use App\Entity\Likes;
use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function getPost(): mixed 
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Post p
            ORDER BY p.idPost DESC '
        );

        return $query->getResult();
    }

    public function getOnePost(int $idPost) : mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Post p
            WHERE p.idPost = :idPost'
        )->setParameter('idPost', $idPost);

        return $query->getResult();
    }

    public function savePost(Post $post, ManagerRegistry $doctrine) : string
    {
        $entityManager = $doctrine->getManager();

        try{
            $entityManager->persist($post);
            $entityManager->flush();

            return 'ok';
        }
        catch(Exception $e){
            return $e;
        }
    }

    public function getLikes(int $idUser) : mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT l
            FROM App\Entity\Likes l
            WHERE l.giveLike = :id'
        )->setParameter('id', $idUser);

        return $query->getResult();
    }

    public function getNbLikesByPost() : mixed
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
        SELECT count(l.give_like)
        FROM post
        LEFT JOIN likes as l
        on post.id_post = l.getliked
        GROUP BY post.id_post
        ORDER BY post.id_post DESC';

        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery();

        return $resultSet->fetchAllAssociative();
    }

    public function whoLikedPost(int $id) : mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT l
            FROM App\Entity\Likes l
            WHERE l.getliked = :post'
        )->setParameter('post', $id)
        ->getResult();

        return $query;
    }

    public function getOneLike(int $user, int $post) : mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT l
            FROM App\Entity\Likes l
            WHERE l.getliked = :post
            AND l.giveLike = :user'
        )->setParameters([
            'post' => $post, 
            'user' => $user
        ])->getResult();

        return $query;
    }

    public function isLikedByUser(int $user, int $post) : bool
    {
        $query = $this->getOneLike($user, $post);

        if (count($query) > 0){
            return true;
        } else {
            return false;
        }
    }

    public function addLike(Likes $like, ManagerRegistry $doctrine) : void
    {
        $entityManager = $doctrine->getManager();
        $entityManager->persist($like);
        $entityManager->flush();
    }

    public function removeLike(Likes $like, ManagerRegistry $doctrine) : void
    {
        $entityManager = $doctrine->getManager();
        $entityManager->remove($like);
        $entityManager->flush();
    }

    public function addComment(Commentaire $comment, ManagerRegistry $doctrine) : void
    {
        $entityManager = $doctrine->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();
    }

    public function getComments()
    {
        $em = $this->getEntityManager();

        $query = $em->createQuery(
            'SELECT c
            FROM App\Entity\Commentaire c');

        return $query->getResult();
    }

    public function getCurrentUserId(string $pseudo): mixed
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT u.idUser
            FROM App\Entity\Users u
            WHERE u.utilisateur = :pseudo
            '
        )->setParameter('pseudo', $pseudo);

        return $query->getResult();
    }

    public function getUser(string $pseudo): mixed
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
}