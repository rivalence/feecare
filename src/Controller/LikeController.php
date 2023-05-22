<?php

namespace App\Controller;

use App\Entity\Likes;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{

    public function __construct(private PostRepository $postRepository, private ManagerRegistry $doctrine)
    {
        # code...
    }

    #[Route('/like/{idPost}', name: 'app_like', methods: ['GET'])]
    public function index(Post $post): JsonResponse
    {
        //Etre sûr que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $user = $this->postRepository->getUser($user->getUserIdentifier())[0];
        $idUser = $user->getIdUser();

        if($this->postRepository->isLikedByUser($idUser, $post->getIdPost())){
            $like = $this->postRepository->getOneLike($idUser, $post->getIdPost())[0];
            $this->postRepository->removeLike($like, $this->doctrine);
        } else {
            $like = new Likes();
            $like->setGiveLike($user);
            $like->setGetliked($post);
            $this->postRepository->addLike($like, $this->doctrine);
        }

        //Tous les utilisateurs qui ont liké le post
        $usersWhoLiked = $this->postRepository->whoLikedPost($post->getIdPost());

        return $this->json([
            'nbLike' => count($usersWhoLiked),
            'userNom' => $user->getNom(),
            'userPrenom' => $user->getPrenom()
        ]);
    }
}
