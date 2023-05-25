<?php

namespace App\Controller;

use App\Entity\Centre;
use App\Repository\CentreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MapController extends AbstractController
{
    public function __construct(
        private CentreRepository $centreRepository
    )
    {
        
    }

    #[Route('/map', name: 'app_map')]
    public function index(): Response
    {
        $centres = $this->centreRepository->getCentres();
        return $this->render('map/index.html.twig', [
            'centres' => $centres,
        ]);
    }

    #[Route('/map/centre-data/{id}', name: 'app_centre_data')]
    public function dataCentre(Centre $centre) : JsonResponse
    {
        return $this->json([
            'lat' => $centre->getLatitude(),
            'lng' => $centre->getLongitude(),
            'nom' => $centre->getNom()
        ]);
    }   
}
