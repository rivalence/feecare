<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegisterType;
use App\Repository\IdentifiantRepository;
use App\Repository\RegisterRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UsersController extends AbstractController
{
    #[Route('/users/register', name: 'app_register', methods: ['POST', 'get'])]
    public function index(Request $request, ValidatorInterface $validator, RegisterRepository $repository, ManagerRegistry $doctrine, IdentifiantRepository $id_repository, UserPasswordHasherInterface $passwordHasher): Response
    {   
        $user = new Users();

        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $user = $form->getData();
            $plaintextPassword = $user->getPassword();
            $errors = $validator->validate($user);
            //Si une erreur a été trouvé dans le formulaire
            if (count($errors) > 0) {
                $errorsString = (string) $errors;
                return $this->render('users/register/index.html.twig', [
                    'form' => $form->createView(),
                    'error' => $errorsString
                ]);
            }

            //On récupère l'identifiant entré par l'utilisateur dans le formulaire d'inscription s'il existe en table Identifiant
            $id_for_identifiant = $id_repository->findId($user->getUtilisateur()); 
            
            if ($id_for_identifiant && strcmp($user->getUtilisateur(), $id_for_identifiant[0]['libelle']) == 0){    //si l'utilisateur a ré-entré le bon pseudo
                //Test de si un utilisateur possède déjà l'ID renseigné
                $user_exist = $repository->findUserByPseudo($user->getUtilisateur());
                if(!$user_exist){
                    //Hashage du mot de passe et écriture des données du formualaire en BDD
                    $hashedPassword = $passwordHasher->hashPassword($user, $plaintextPassword);
                    $user->setMdp($hashedPassword);

                    //Nom et prenom transformés en majuscules
                    $nom = $user->getNom();
                    $nom = strtoupper($nom);
                    $prenom = $user->getPrenom();
                    $prenom = strtoupper($prenom);
                    $user->setNom($nom);
                    $user->setPrenom($prenom);

                    $check = $repository->save($user, $doctrine);
                    if($check == 1){    //Si l'écriture est bonne, on renvoie vers la connexion
                        return $this->redirectToRoute('app_login');
                    }
                    else{
                        $this->addFlash('fail_save_register', "Problème d'écriture en base de données");
                        return $this->render('users/register/index.html.twig', [
                            'form' => $form->createView(),
                            'error' => ''
                        ]);
                    }
                }
                else{       //Mauvais ID entré à l'inscription
                    $this->addFlash('id_exist', 'Cet identifiant est déjà utilisé');
                    return $this->render('users/register/index.html.twig', [
                        'form' => $form->createView(),
                        'error' => ''
                    ]);
                }
                
            }
            else{
                $this->addFlash('mauvais_id', "L'ID entré n'est pas bon");
                    return $this->render('users/register/index.html.twig', [
                        'form' => $form->createView(),
                        'error' => ''
                    ]);
            }
            
        }

        return $this->render('users/register/index.html.twig', [
            'form' => $form->createView(),
            'error' => ''
        ]);
    }

    
    #[Route('/users/login', name: 'app_login', methods: ['POST', 'get'])]
    public function new(AuthenticationUtils $authenticationUtils): Response
    {
        //Erreurs de connexion
        $error = $authenticationUtils->getLastAuthenticationError();

        //Dernier login entré par l'utilisateur
        $lastUserName = $authenticationUtils->getLastUsername();

        return $this->render('users/login/index.html.twig', [
            'error' => $error, 
            'last_username' => $lastUserName
        ]);
    }

    #[Route('/users/logout', name: 'app_logout')]
    public function logout()
    {
        //Nothing to do
    }
}
