<?php

namespace App\Controller;

use App\Entity\Creneaux;
use App\Entity\Users;
use App\Form\CreneauxType;
use App\Repository\CreneauxRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreneauxController extends AbstractController
{
    public function __construct(
        private CreneauxRepository $repository, 
        private ManagerRegistry $doctrine
    )
    {
        
    }
    
    #[Route('/creneaux', name: 'app_creneaux', methods: ['post', 'get'])]
    public function index(Request $request): Response
    {
        //Etre sûr que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $user = new Users();
        //Utilisateur connecté
        $user = $this->getUser();

        //Création du formulaire de dispos
        $creneau = new Creneaux();
        $form = $this->createForm(CreneauxType::class, $creneau);
        $form->handleRequest($request);

        //Récupération des dispos déjà présentes pour l'éducateur
        $educateurKey = $this->repository->getCurrentUserId($user)[0]["idUser"];
        $list_creneaux = $this->repository->fetchCreneaux($educateurKey);

        if($form->isSubmitted() && $form->isValid()){
            $creneau = $form->getData();  

            //On récupère l'ID du user courant et màj date et heure
            $creneau->setEducateurKey($user);

            foreach($list_creneaux as $c){        //On vérifie si le même créneau a déjà été ajouté
                if (($c->getDateCreneau() == $creneau->getDateCreneau()) && ($c->getTimeCreneau() == $creneau->getTimeCreneau())){
                    $this->addFlash('creneau_exist', 'Ce créneau existe déjà dans votre liste.');
                    return $this->redirectToRoute('app_creneaux');
                }
            }

            //Sauvegarde du formulaire dans la table créneaux avec vérif des erreurs
            $check = $this->repository->saveCreneau($creneau, $this->doctrine);
            if (strcmp($check, 'ok') == 0){     //Si la sauvegarde est passée
                $this->addFlash('creneau_ajoute', 'Disponibilités mises à jour !'); //Message d'alerte
                return $this->redirectToRoute('app_creneaux');
            }
            else{
                //Retourner le message dans un log
                return $this->redirectToRoute('app_creneaux');
            }
        }

        //Tableau de formulaires pour supprimer un rdv 
        $i = 0;
        $tabForm = [];
        $tabFormView = [];
        while ($i < count($list_creneaux)){
            $form2 = $this->createFormBuilder()
                ->add('id', HiddenType::class)
                ->add('submit', SubmitType::class, [
                    'attr' => [
                        'class' => 'btn btn-outline-primary'
                    ],
                    'label' => 'Supprimer ce créneau'
                ])
                ->setMethod('post')
                ->getForm();

            $form2->handleRequest($request);
            $tabForm[$i] = $form2;       //Les entités à manipuler
            $tabFormView[$i] = $form2->createView();     //Les vues du form pour twig
            $i++;
        }

        if ($tabForm && $tabForm[0]->isSubmitted()){
            $idCreneauToRemove = $tabForm[0]->getData();
            $creneauToRemove = $this->repository->getCreneauToRemove($idCreneauToRemove['id']);
            $check = $this->repository->removeCreneau($creneauToRemove[0], $this->doctrine);
            if (strcmp($check, 'ok') == 0){
                $this->addFlash('creneau_removed', 'Créneau supprimé avec succès');
                return $this->redirectToRoute('app_creneaux');
            }else{
                //Retourner l'erreur vers un log
                return $this->redirectToRoute('app_creneaux');
            }
        }

        return $this->render('creneaux/index.html.twig', [
            'form' => $form->createView(),
            'list_creneaux' => $list_creneaux,
            'tab_form_view' => $tabFormView
        ]);
    }
}
