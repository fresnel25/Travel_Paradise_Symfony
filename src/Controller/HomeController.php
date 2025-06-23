<?php

namespace App\Controller;

use App\Repository\VisitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(Security $security, VisitRepository $visitRepository): Response
    {
        // Vérifie si l'utilisateur est connecté
        if (!$security->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        $visits = $visitRepository->findAll();

        $locations = [];
        foreach ($visits as $visit) {
            $country = $visit->getCountry();

            if ($coords = $this->getCoordinatesForCountry($country)) {
                $locations[] = [
                    'country' => $country,
                    'lat' => $coords['lat'],
                    'lng' => $coords['lng'],
                ];
            }
        }

        // Si connecté, affiche le dashboard
        return $this->render('home/dashboard.html.twig', [
            'visits' => $visits,
            'locations' => $locations,
        ]);
    }

    private function getCoordinatesForCountry(string $country): ?array
    {
        $map = [
            'France' => ['lat' => 46.2276, 'lng' => 2.2137],
            'Maroc' => ['lat' => 31.7917, 'lng' => -7.0926],
            'Canada' => ['lat' => 56.1304, 'lng' => -106.3468],
            // Ajoute d’autres pays ici
        ];

        return $map[$country] ?? null;
    }
}
