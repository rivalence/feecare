<?php

namespace App\Controller;
use App\Entity\Users;
use App\Entity\Messages;
use App\Entity\Traiter;
use App\Repository\TraiterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class MessageController extends AbstractController
{
    #[Route('/messagerie', name: 'messagerie')]
    public function index(TraiterRepository $repository): Response
    {
        //Etre sûr que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        //User courant
        $user = $this->getUser();

        //Contrôle du type de user et interface à renvoyer
        if (strpos($user->getUserIdentifier(), 'E') === 0) {
            // Interface educateur
            $list_patient = $repository->fetchAllPatient($repository->getCurrentUserId($user)[0]["idUser"]);
            return $this->render('message/educateur/index.html.twig', [
                'list_patient' => $list_patient
            ]);
        } else {
            // Interface famille
            $list_traitants = $repository->fetchAllTraitantChat($repository->getCurrentUserId($user)[0]["idUser"]);
            return $this->render('message/famille/index.html.twig', [
                'list_traitant' => $list_traitants
            ]);
        }
    }

    
    #[Route('/conversation/{userId}', name: 'conversation')]
    public function conversation($userId, Request $request, EntityManagerInterface $entityManager): Response
    {
        //Etre sûr que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
    
        //User courant
        $user = $this->getUser();
    
        // Récupérer le destinataire
        $destinataire = $entityManager->getRepository(Users::class)->find($userId);
    
        // Récupérer le message
        $message = $request->request->get('message');
    
       
        if(!empty($message)){
            try {
                $messageEntity = new Messages();
                $messageEntity->setAuteur($user);
                $messageEntity->setDestinataire($destinataire);
                $messageEntity->setDataMsg($message);
                $entityManager->persist($messageEntity);
                $entityManager->flush();
                //return $this->json(['success' => true]);
                $this->addFlash('success', 'Le message a été envoyé avec succès.');
            } catch (\Exception $e) {
                //return $this->json(['success' => false, 'error' => 'Le message n\'a pas pu être envoyé. Veuillez réessayer plus tard.']);

                $this->addFlash('error', 'Le message n\'a pas pu être envoyé. Veuillez réessayer plus tard.');
            }
           
        }
        
    
        // Récupérer tous les messages de la conversation entre les deux utilisateurs
        $messages = $entityManager->getRepository(Messages::class)->findBy(
            ['auteur' => [$user, $destinataire], 'destinataire' => [$user, $destinataire]]
        );

        //Contrôle du type de user et interface à renvoyer
        if (strpos($user->getUserIdentifier(), 'E') === 0) {
            // Interface educateur
           // Transmettre les messages à la vue
        return $this->render('message/educateur/chat.html.twig', [
            'messages' => $messages,
            'destinataire' => $destinataire
        ]);
        } else {
            // Interface famille
           // Transmettre les messages à la vue
            return $this->render('message/famille/chat.html.twig', [
                'messages' => $messages,
                'destinataire' => $destinataire
            ]);
        }
    
        
    }
}