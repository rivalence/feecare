<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Users;
use App\Form\PostType;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(Request $request, PostRepository $postRepository, ManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        //Etre sûr que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $user = new Users();
        //Utilisateur connecté
        $user = $this->getUser();

        //Formulaire d'ajout du post
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        //Soumission du formulaire
        if ($form->isSubmitted() && $form->isValid()){
            $post = $form->getData();
            $post->setDatePost(new DateTime());
            $post->setTimePost(new DateTime());
            $post->setAuteur($user);

            //Traitement de l'image
            $file = $form->get('dataPost')->getData();
            $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFileName);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

            // Move the file to the directory where brochures are stored
            try{
                $file->move(
                    $this->getParameter('file_directory'),
                    $newFilename
                );
            }catch (FileException $e){
                $this->addFlash('upload_failed', $e);
            }

            //Rajout du fichier dans l'entité Post à sauvegarder
            $post->setDataPost($newFilename);

            //Enregistrement du post
            $check = $postRepository->savePost($post, $doctrine);

            //Contrôle d'erreurs sur la sauvegarde du post
            if(strcmp($check, 'ok') == 0){
                return $this->redirectToRoute('app_actualite');
            }
            else{
                $this->addFlash('save_post_failed', $check);
                return $this->redirectToRoute('app_post');
            }
        }

        return $this->render('post/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
