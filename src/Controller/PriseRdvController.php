<?php

namespace App\Controller;

use App\Entity\Rdv;
use App\Entity\Users;
use App\Entity\Creneaux;
use App\Form\ChoixCreneauType;
use App\Model\ChoixCreneauData;
use Symfony\Component\Mime\Email;
use App\Repository\PriseRdvRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;

class PriseRdvController extends AbstractController
{
    public function __construct(
        private PriseRdvRepository $repository, 
        private ManagerRegistry $doctrine, 
        private MailerInterface $mailer)
    {
        
    }
    #[Route('/prise/rdv', name: 'app_prise_rdv')]
    public function index(
        Request $request,
        SessionInterface $session): Response
    {
        //Etre sûr que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $user = new Users();
        //Utilisateur connecté
        $user = $this->getUser();
        $user = $this->repository->getCurrentUser($user->getUserIdentifier());

        //Instance pour les créneaux du traitant
        $creneaux = new Creneaux();

        //Recupération de l'ID du traitant 
        $nom = $session->get('name')["nom"];
        $idTraitant = $this->repository->findIdUser($nom);
        $creneaux = $this->repository->getCreneaux($idTraitant[0]['idUser']);

        //Tableau de Formulaires pour récupérer les créneaux de l'éducateur
        $i = 0;
        $tabForm = [];
        $tabFormView = [];
        $choix_creneau = new ChoixCreneauData();
        while ($i < count($creneaux)) {
            $form = $this->createForm(ChoixCreneauType::class, $choix_creneau);
            
            //Attente de données
            $form->handleRequest($request);

            $tabFormView[$i] = $form->createView();
            $tabForm[$i] = $form;
            $i++;
        }
        
        foreach ($tabForm as $form) {
            //Si le choix est fait
            if ($form->isSubmitted()){  
                //Récupération des données du créneau
                $creneauChoisi = new Creneaux();
                $creneauChoisi = $this->repository->getCreneauChoisi($choix_creneau->id);

                //On récupère toutes les données du traitant
                $traitant = new Users();
                $traitant= $this->repository->getTraitant($idTraitant[0]['idUser']);

                //Sauvegarde du rdv en BDD
                $rdv = new Rdv();
                $rdv->setDateRdv($creneauChoisi[0]->getDateCreneau());
                $rdv->setTimeRdv($creneauChoisi[0]->getTimeCreneau());
                $rdv->setEducateurKey($traitant[0]);
                $rdv->setFamilleKey($user[0]);

                //Contrôle d'erreurs
                $check = $this->repository->saveRdv($rdv, $this->doctrine);
                if (strcmp($check, 'ok') == 0){
                    $session->remove('name');
                    $this->addFlash('rdv_success', 'Rendez-vous ajouté avec succès !');
                    // $email = (new Email())
                    // ->from($user[0]->getEmail())
                    // ->to($traitant[0]->getEmail())
                    // ->subject('Rendez-vous annulé.')
                    // ->text($user[0]->getNom(). " ".$user[0]->getPrenom(). " a pris rendez-vous avec vous.")
                    // ->html('<p>See Twig integration for better HTML integration!</p>');

                    // $mailer->send($email);

                    //On supprime le créneau réservé pour le rendez-vous
                    $this->repository->removeCreneau($creneauChoisi[0], $this->doctrine);

                    return $this->redirectToRoute('app_traiter');
                } else {
                    $this->addFlash('rdv_fail', $check);
                    return $this->redirectToRoute('app_prise_rdv');
                }
            }
        }

        return $this->render('prise_rdv/index.html.twig', [
            'tab_form' => $tabFormView,
            'creneaux' => $creneaux  
        ]);
    }
}
