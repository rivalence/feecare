<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Identifiant;
use App\Form\IdentifiantType;
use App\Model\IdentifiantData;
use App\Repository\IdentifiantRepository;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class HomeController extends AbstractController
{
    public function __construct(
        private IdentifiantRepository $repository, 
        private ValidatorInterface $validator, 
        private AuthorizationCheckerInterface $authorizationCheckerInterface)
    {
        
    }
    #[Route('/', 'home.index', methods: ['POST', 'GET'])]
    public function index(Request $request) : Response
    {
        //Contrôle d'authentification
        if($this->authorizationCheckerInterface->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            return $this->redirectToRoute('app_actualite');
        }

        //formulaire de base renvoyer sur la page d'accueil

        $identifiantData = new IdentifiantData();

        $form = $this->createForm(IdentifiantType::class, $identifiantData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {  

            //Controle des erreurs de formulaire
            $errors = $this->validator->validate($identifiantData);
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                return $this->render('home.html.twig', [
                    'form' => $form->createView(),
                    'error' => $errorsString
                ]);
            }

            //Si l'ID existe en BDD, on passe à la connexion ou l'inscription
            if ($this->repository->findId($identifiantData->libelle)){
                //Sauvegarder le pseudo du user dans la table Users
                $user_exist = $this->repository->findIdInUser($identifiantData->libelle);

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


