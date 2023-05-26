<?php

namespace App\Controller;

use App\Entity\Rdv;
use App\Entity\Users;
use App\Repository\RdvRepository;
use Symfony\Component\Mime\Email;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RdvController extends AbstractController
{
    public function __construct(
        private RdvRepository $repository, 
        private  ManagerRegistry $doctrine,
        private  MailerInterface $mailer
    )
    {
        
    }

    #[Route('/rdv', name: 'app_rdv')]
    public function index(Request $request): Response
    {
        //Etre sûr que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //User courant
        $user = new Users();
        $user = $this->getUser();
        //Récupératon des rdv du user actif
        $user = $this->repository->getCurrentUser($user->getUserIdentifier());

        //Contrôle du type de user et interface à renvoyer
        $pseudo = $user[0]->getUtilisateur();
        if(!strcmp($pseudo[0], 'F'))    //interface famille
        {    
            //On stocke dans un tableau uniquement les rdvs NON-ASSURE
            $rdvListFetch = $this->repository->getRdvsFamille($user[0]->getIdUser());
            $rdvList = [];

            foreach ($rdvListFetch as $rdv) {
                if(!strcmp($rdv[0]->getStatut(), "ASSURE")) continue;
                else array_push($rdvList, $rdv);
            }

            //tableau de Formulaires pour le bouton d'annulation du rdv
            $tabForm = [];
            $tabFormView = [];
            $i = 0;

            while($i < count($rdvList)) {
                $form = $this->createFormBuilder()
                    ->add('id', HiddenType::class)
                    ->add('submit', SubmitType::class, [
                        'attr' => [
                            'class' => 'btn btn-outline-primary'
                        ],
                        'label' => 'Annuler'
                    ])
                    ->setMethod('post')
                    ->getForm();

                $tabFormView[$i] =$form->createView();
                $form->handleRequest($request);
                $tabForm[$i] = $form;
                $i++;
            }

            //Traitement après soumission de l'annulation du rdv
            if ($tabForm && $tabForm[0]->isSubmitted()){
                $idRdvToRemove = $tabForm[0]->getData();
                $rdvToRemove = $this->repository->getRdv($idRdvToRemove['id']);
                $this->repository->removeRdv($rdvToRemove[0], $this->doctrine);

                foreach ($rdvList as $rdv) {
                    if($rdv[0]->getIdRdv() == $idRdvToRemove['id']){  
                        $email = (new Email())
                        ->to($rdv["email"])
                        ->subject('Rendez-vous annulé.')
                        ->html("<p>Votre rendez-vous du ". $rdv[0]->getDateRdv()->format("d-m-Y") ." à ".$rdv[0]->getTimeRdv()->format("H-i").
                        " vient d'être annulé par ".$user[0]->getNom(). " ".$user[0]->getPrenom().".<p>");

                        $this->mailer->send($email);
                    }
                }
                
                
                return $this->redirectToRoute('app_rdv');
            }
            
            return $this->render('rdv/famille/index.html.twig',[
                'rdv_list' => $rdvList,
                'tab_form_view' => $tabFormView
            ]);
        }
        else if(strcmp($pseudo[0], 'E') == 0){
            $rdvListFetch = $this->repository->getRdvsEducateur($user[0]->getIdUser());
            $rdvList = [];

            foreach ($rdvListFetch as $rdv) {
                if(strcmp($rdv[0]->getStatut(), "ASSURE") == 0 ) continue;
                else array_push($rdvList, $rdv);
            }

            $i = 0;
            while($i < count($rdvList)) {
                $form = $this->createFormBuilder()
                    ->add('id', HiddenType::class)
                    ->add('submit', SubmitType::class, [
                        'attr' => [
                            'class' => 'btn btn-outline-danger'
                        ],
                        'label' => 'Annuler'
                    ])
                    ->setMethod('post')
                    ->getForm();

                $form->handleRequest($request);

                $rdvList[$i]["annuleForm"] = $form->createView();

                //Traitement après soumission de l'annulation du rdv
                if ($form->isSubmitted() && $form->isValid()){
                    $idRdvToRemove = $form->getData();
                    $rdvToRemove = $this->repository->getRdv($idRdvToRemove['id']);
                    $this->repository->removeRdv($rdvToRemove[0], $this->doctrine);

                    foreach ($rdvList as $rdv) {
                        if($rdv[0]->getIdRdv() == $idRdvToRemove['id']){
                            $email = (new Email())
                            ->to($rdv["email"])
                            ->subject('Rendez-vous annulé.')
                            ->html("<p>Votre rendez-vous du ". $rdv[0]->getDateRdv()->format("d-m-Y") ." à ".$rdv[0]->getTimeRdv()->format("H-i").
                            " vient d'être annulé par ".$user[0]->getNom(). " ".$user[0]->getPrenom().".<p>");
    
                            $this->mailer->send($email);
                        }
                    }
                    
                    return $this->redirectToRoute('app_rdv');
                }
                $i++;
            }

            return $this->render('rdv/educateur/index.html.twig',[
                'rdv_list' => $rdvList
            ]);
        }
    }

    #[Route("/rdvAssure/{idRdv}", name: "app_rdv_assure", methods: ['GET'])]
    public function rdvAssure(Rdv $rdv, RdvRepository $repository, ManagerRegistry $doctrine)
    {
        $rdvToAssure = clone $rdv;
        $rdvToAssure->setStatut('ASSURE');
        $repository->saveRdv($rdvToAssure, $doctrine);

        $repository->removeRdv($rdv, $doctrine);
        return $this->redirectToRoute('app_rdv');
    }
}
