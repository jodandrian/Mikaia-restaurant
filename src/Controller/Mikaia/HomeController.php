<?php

namespace App\Controller\Mikaia;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'mikaia_home')]
    public function index(): Response
    {
        // Données du restaurant (suggestions et plats phares)
        $plats = [
            [
                'nom' => 'Le Burger Signature Mikaia',
                'categorie' => 'Plat Principal',
                'prix' => '16.50 €',
                'desc' => 'Bœuf fermier, cheddar affublé, oignons caramélisés et sauce secrète de la maison.',
                'badge' => 'Coup de cœur'
            ],
            [
                'nom' => 'Salade Burrata & Figues',
                'categorie' => 'Entrée / Fraîcheur',
                'prix' => '13.00 €',
                'desc' => 'Burrata crémeuse des Pouilles, figues fraîches de saison, pignons torréfiés.',
                'badge' => 'De saison'
            ],
            [
                'nom' => 'Le Fondant Chocolat-Passion',
                'categorie' => 'Dessert',
                'prix' => '7.50 €',
                'desc' => 'Cœur coulant grand cru, coulis exotique et éclat de noisettes caramélisées.',
                'badge' => 'Incontournable'
            ],
        ];

        return $this->render('mikaia/home/index.html.twig', [
            'plats' => $plats,
        ]);
    }
}
