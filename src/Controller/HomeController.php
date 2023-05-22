<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Identifiant;
use App\Form\IdentifiantType;
use App\Repository\IdentifiantRepository;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HomeController extends AbstractController
{
    #[Route('/', 'home.index', methods: ['POST', 'GET'])]
    public function index(Request $request, IdentifiantRepository $repository, ValidatorInterface $validator, AuthorizationCheckerInterface $authorizationCheckerInterface) : Response
    {
        //Contrôle d'authentification
        if($authorizationCheckerInterface->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirectToRoute('app_actualite');
        }

        //formulaire de base renvoyer sur la page d'accueil

        $identifiant = new Identifiant();

        $form = $this->createForm(IdentifiantType::class, $identifiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {  
            //On récupère les données du formulaire       
            $identifiant = $form->getData();

            $errors = $validator->validate($identifiant);
            //Si une erreur a été trouvé dans le formulaire
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                return $this->render('home.html.twig', [
                    'form' => $form->createView(),
                    'error' => $errorsString
                ]);
            }

            //Si l'ID existe en BDD, on passe à la connexion ou l'inscription
            if ($repository->findId($identifiant->getLibelle())){
                //Sauvegarder le pseudo du user dans la table Users
                $user_exist = $repository->findIdInUser($identifiant->getLibelle());
                if (!$user_exist){  //Si l'identifiant n'est rataché à aucun utilisateur
                    return $this->redirectToRoute('app_register');
                }
                else {
                    return $this->redirectToRoute('app_login');
                }    
            }
            else{
                $this->addFlash(
                    'notice',
                    'Identifiant invalide !'
                );

                return $this->render('home.html.twig', [
                    'form' => $form->createView(),
                    'error' => ''
                ]);
            }
        }

        return $this->render('home.html.twig', [
            'form' => $form->createView(),
            'error' => ''
        ]);
    }
}


