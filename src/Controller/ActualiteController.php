<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Model\CommentaireData;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ActualiteController extends AbstractController
{
    public function __construct(private PostRepository $postRepository,private ManagerRegistry $doctrine)
    {
    }

    #[Route('/actualite', name: 'app_actualite')]
    public function index(Request $request): Response
    {   
        //Etre sûr que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //Récupération des informations du user
        $user = $this->getUser();

        $pseudo = $user->getUserIdentifier();

        //Plateforme educateur 
        if(strcmp($pseudo[0],'E') == 0){
            //Posts likés par le user courant
            $idUser = $this->postRepository->getCurrentUserId($pseudo)[0]['idUser'];
            $likedPosts = $this->postRepository->getLikes($idUser);

            $feed = $this->feed($request, $pseudo);

            return $this->render('actualite/educateur.html.twig', [
                'liked_posts' => $likedPosts,
                'feeds' => $feed
            ]);

        }

        //Plateforme famille
        else if(strcmp($pseudo[0],'F') == 0){
            //Posts likés par le user courant
            $idUser = $this->postRepository->getCurrentUserId($pseudo)[0]['idUser'];
            $likedPosts = $this->postRepository->getLikes($idUser);

            $feed = $this->feed($request, $pseudo);
            return $this->render('actualite/famille.html.twig', [
                'liked_posts' => $likedPosts,
                'feeds' => $feed
            ]);
        }

        //Plateforme admin
        else if(strcmp($pseudo[0], 'A' == 0)){
            return $this->render('actualite/admin.html.twig');
        }    
    }

    public function addComment(CommentaireData $commentaireData) : JsonResponse
    {
        //Récupération des informations du user
        $user = $this->getUser();
        $user = $this->postRepository->getUser($user->getUserIdentifier())[0];

        $commentaire = new Commentaire();
        $commentaire->setAuteur($user);
        $commentaire->setPost($this->postRepository->getOnePost($commentaireData->postId)[0]);
        $commentaire->setContenu($commentaireData->contenu);

        $this->postRepository->addComment($commentaire, $this->doctrine);

        return new JsonResponse([
            'idPost' => $commentaireData->postId,
            'contenu' => $commentaireData->contenu,
            'auteur_name' => $user->getNom(),
            'auteur_firstName' => $user->getPrenom()
        ]);
    }

    public function feed(Request $request, string $pseudo)
    {
        //On lit tous les posts de la base de données et leur nombre de likes
        $listPost = $this->postRepository->getPost(); 
        $likesByPost = $this->postRepository->getNbLikesByPost();
        
        //Posts likés par le user courant
        $idUser = $this->postRepository->getCurrentUserId($pseudo)[0]['idUser'];
        $likedPosts = $this->postRepository->getLikes($idUser);

        //Tous les commentaires
        $comments = $this->postRepository->getComments();

        //Nouveau commentaire à sauvegarder
        $commentaireData = new CommentaireData();
        $formComment = $this->createForm(CommentaireType::class, $commentaireData)->handleRequest($request);
        $listFormCommentView = [];
        for ($i=0; $i < count($listPost); $i++) { 
            $listFormCommentView[$i] = $formComment->createView();
        }

        //Si soumission du formulaire
        if($formComment->isSubmitted() && $formComment->isValid()){

            return $this->addComment($commentaireData);
        }
        

        //Tableau qui regroupe tous les composants d'un post
        $feed = [];
        for ($i=0; $i < count($listPost); $i++) { 
            $feed[$i]["post"] = $listPost[$i];
            $feed[$i]["nbLikes"] = $likesByPost[$i];
            $feed[$i]["formComment"] = $listFormCommentView[$i];
            $feed[$i]["comments"] = $comments;

            if($likesByPost[$i]["count"] > 0)   //S'il y'a des likes sur le post
            {
                //On lit les users qui ont liké et on fetch leurs noms
                $tempListLikes = $this->postRepository->whoLikedPost($listPost[$i]->getIdPost());
                $feed[$i]["listLikesNames"] = [];
                foreach ($tempListLikes as $temp) {
                    array_push($feed[$i]["listLikesNames"], $temp->getGiveLike()->getNom() .' '. $temp->getGiveLike()->getPrenom());
                }
            }
            else{
                $feed[$i]["listLikesNames"] = "Pas de like sur ce post";
            }
        }

        return $feed;
    }
}
