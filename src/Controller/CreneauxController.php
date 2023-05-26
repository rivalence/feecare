<?php

namespace App\Controller;

use App\Entity\Creneaux;
use App\Entity\Users;
use App\Form\CreneauxType;
use App\Model\CreneauxData;
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
        $creneauData = new CreneauxData();
        $form = $this->createForm(CreneauxType::class, $creneauData);
        $form->handleRequest($request);

        //Récupération des dispos déjà présentes pour l'éducateur
        $educateurKey = $this->repository->getCurrentUserId($user)[0]["idUser"];
        $list_creneaux = $this->repository->fetchCreneaux($educateurKey);

        if($form->isSubmitted() && $form->isValid()){
            //Type d'entrée de créneaux voulu
            if(strcmp($creneauData->type, 'Semaine') == 0){
                for ($i=0; $i < $creneauData->recurrence; $i++) {
                    //Création de l'entité créneau à sauver
                    $creneau = new Creneaux();
                    $creneau->setEducateurKey($user);
                    $creneau->setTimeCreneau($creneauData->timeCreneau);
                    $creneau->setDateCreneau($creneauData->dateCreneau); 
                    $result = $this->saveCreneau($creneau, $list_creneaux);
                    if(strcmp($result, 'no') == 0) {
                        $this->addFlash('creneau_save_fail', 'Erreur de sauvegarde... Veuillez rééssayer');
                        return $this->redirectToRoute('app_creneaux');
                    }

                    $creneauData->dateCreneau = $creneauData->dateCreneau->modify("+1 weeks");
                }

                $this->addFlash('creneau_ajoute', 'Disponibilités mises à jour !'); //Message d'alerte
                return $this->redirectToRoute('app_creneaux');
            }
            else {
                //Création de l'entité créneau à sauver
                $creneau = new Creneaux();
                $creneau->setEducateurKey($user);
                $creneau->setDateCreneau($creneauData->dateCreneau);
                $creneau->setTimeCreneau($creneauData->timeCreneau);
                $result = $this->saveCreneau($creneau, $list_creneaux);

                //Rediriger direct sur la page creneau si erreur
                if(!strcmp($result, 'no')) {
                    $this->addFlash('creneau_save_fail', 'Erreur de sauvegarde... Veuillez rééssayer');
                    return $this->redirectToRoute('app_creneaux');
                }

                $this->addFlash('creneau_ajoute', 'Disponibilités mises à jour !'); //Message d'alerte
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

    public function saveCreneau(Creneaux $creneau, array $list_creneaux): string
    {
        foreach($list_creneaux as $c){        //On vérifie si le même créneau a déjà été ajouté
            if (($c->getDateCreneau() == $creneau->getDateCreneau()) && ($c->getTimeCreneau() == $creneau->getTimeCreneau())){
                $this->addFlash('creneau_exist', 'Le créneau du '.$creneau->getDateCreneau()->format('Y-m-d').' à '.
                $creneau->getTimeCreneau()->format('H:i').' existe déjà dans votre liste.');
                return 'no';
            }
        }

        //Sauvegarde du formulaire dans la table créneaux avec vérif des erreurs
        $check = $this->repository->saveCreneau($creneau, $this->doctrine);
        if (strcmp($check, 'ok') == 0){     //Si la sauvegarde est passée
            return 'yes';
        }
        else{
            //Retourner le message dans un log
            return 'no';
        }
    }
}