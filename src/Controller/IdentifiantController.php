<?php

namespace App\Controller;

use App\Entity\Identifiant;
use App\Form\IdentifiantType;
use App\Repository\IdentifiantRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IdentifiantController extends AbstractController
{
    public function __construct(
        private IdentifiantRepository $identifiantRepository, 
        private ManagerRegistry $doctrine)
    {
        
    }
    
    #[Route('/identifiant', name: 'app_identifiant')]
    public function index(Request $request): Response
    {
        //Etre sûr que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $identifiant = new Identifiant();

        $form = $this->createForm(IdentifiantType::class, $identifiant);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if($this->identifiantRepository->findId($identifiant->getLibelle())){
                $this->addFlash("id_exist", "Cet identifiant existe déjà");
                return $this->redirectToRoute('app_identifiant');
            }
            else {
                $this->identifiantRepository->addIdentifiant($identifiant, $this->doctrine);
                $this->addFlash("id_success", "Identifié rajouté avec succès");
            }
        }

        return $this->render('identifiant/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
