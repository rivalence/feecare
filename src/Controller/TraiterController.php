<?php

namespace App\Controller;

use App\Entity\Traiter;
use App\Entity\Users;
use App\Form\TraiterType;
use App\Model\TraiterData;
use App\Repository\TraiterRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class TraiterController extends AbstractController
{

    public function __construct(
        private  TraiterRepository $repository,
        private ManagerRegistry $doctrine,
    )
    {
        
    }
    #[Route('/traiter', name: 'app_traiter')]
    public function index(Request $request, SessionInterface $session) : Response
    {
        //Etre sûr que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //User courant
        $user = new Users();
        $user = $this->getUser();

        //Contrôle du type de user et interface à renvoyer
        $pseudo = $user->getUserIdentifier();
        if (strcmp($pseudo[0], 'F') == 0){       //Interface famille

            $traiter = new Traiter();

            $form = $this->createFormBuilder()
                    ->add('nom', HiddenType::class, [
                        'attr' => [
                            'name' => 'traitantPourRdv'
                        ]
                    ])
                    ->add('submit', SubmitType::class, [
                        'attr' => [
                            'class' => 'btn btn-outline-primary',
                        ],
                        'label' => 'Prendre Rendez-vous avec ce traitant'
                    ])
                    ->setMethod('post')
                    ->getForm();

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                //Récupération du nom du traitant avec qui le patient veut prendre rdv
                $session->set('name', $form->getData());
                return $this->redirectToRoute('app_prise_rdv');
                
                //Passer le champ nom vers la page rdv.twig avec l'url
                //recup le nom dans rdv, afficher les créneaux dispos et valider un
            }
            //Liste des traitants pour le user courant
            $listTraitants = $this->repository->fetchAllTraitant($this->repository->getCurrentUserId($user)[0]["idUser"]);
            return $this->render('traiter/famille/index.html.twig', [
                'form' => $form->createView(),
                'list_traitant' => $listTraitants
            ]);
        }
        
        
        return $this->render('traiter/admin/index.html.twig', [
        ]);
    }

    #[Route('/traiter/add', name: 'app_traiter_add')]
    public function addPatient(Request $request) : Response
    {
        //Etre sûr que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $traiter = new Traiter();
        $traiterData = new TraiterData();
        //Formulaire d'ajout d'un patien à un traitant
        $form = $this->createForm(TraiterType::class, $traiterData);

        //En attente de requete
        $form->handleRequest($request);

        //Après soumission du formulaire si l'éducateur veut rajouter un patient à sa liste
        if($form->isSubmitted() && $form->isValid()){
            $nom_patient = strtoupper($traiterData->famille);
            $nom_educateur = strtoupper($traiterData->educateur);

            //Récupération des infos de l'utilisateur famille
            $user_famille = $this->repository->findUserByName($nom_patient);

            //Infos de l'educateur
            $user_educateur = $this->repository->findUserByName($nom_educateur);
            
            if($user_famille && $user_educateur){  //S'il existe
                //Liste des patients pris en charge par l'educateur
                $list_patient = $this->repository->fetchAllPatient($user_educateur[0]->getIdUser());
                
                foreach($list_patient as $patient){ //On vérifie si le user ne fait déjà partie de la liste des patients
                    if ($patient == $user_famille[0]){
                        $this->addFlash('patient_exist', 'Ce patient fait déjà partie de la liste de cet educateur');
                        return $this->redirectToRoute('app_traiter');
                    }
                }

                $traiter->setEducateurKey($user_educateur[0]);
                $traiter->setFamilleKey($user_famille[0]);
                $check = $this->repository->addPatient($traiter, $this->doctrine);
                if (strcmp($check, 'ok') == 0){
                    $this->addFlash('save_success', 'Patient ajouté avec succès!');
                    return $this->redirectToRoute('app_traiter_add');
                }else{  //Message d'erreur et redirection
                    //Rediriger l'erreur vers un fichier log
                    return $this->redirectToRoute('app_traiter_add');
                }

            }else{  //Si le User de nom $name n'existe pas
                $this->addFlash('user_introuvable',"L'utilisateur entré n'existe pas");
                return $this->redirectToRoute('app_traiter_add');
            }
        }

        return $this->render('traiter/admin/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/traiter/remove', name: 'app_traiter_remove')]
    public function removePatient(Request $request) : Response
    {
        //Etre sûr que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $traiter = new Traiter();
        $traiterData = new TraiterData();
        //Formulaire d'ajout d'un patien à un traitant
        $form = $this->createForm(TraiterType::class, $traiterData);

        //En attente de requete
        $form->handleRequest($request);

        //Après soumission du formulaire si l'éducateur veut rajouter un patient à sa liste
        if($form->isSubmitted() && $form->isValid()){
            $nom_patient = strtoupper($traiterData->famille);
            $nom_educateur = strtoupper($traiterData->educateur);

            //Récupération des infos de l'utilisateur famille
            $user_famille = $this->repository->findUserByName($nom_patient);

            //Infos de l'educateur
            $user_educateur = $this->repository->findUserByName($nom_educateur);

            if($user_famille && $user_educateur){  //S'il existe
                //Liste des patients pris en charge par l'educateur
                $list_patient = $this->repository->fetchAllPatient($user_educateur[0]->getIdUser());

                foreach($list_patient as $patient){ //On vérifie si le user ne fait déjà partie de la liste des patients
                    if ($patient == $user_famille[0]){
                        $traiter = new Traiter();
                        $traiter->setEducateurKey($user_educateur[0]);
                        $traiter->setFamilleKey($user_famille[0]);
                        $check = $this->repository->removePatient($traiter, $this->doctrine);
                        $this->addFlash('save_success', 'Patient supprimé avec succès!');
                        return $this->redirectToRoute('app_traiter_remove');
                    }
                } 
                
                $this->addFlash('patient_exist', 'Ce patient ne fait pas partie de la liste de cet éducateur');
                return $this->redirectToRoute('app_traiter_remove');
        }
    }

        return $this->render('traiter/admin/index.html.twig', [
        'form' => $form->createView()
        ]);    
    }
}