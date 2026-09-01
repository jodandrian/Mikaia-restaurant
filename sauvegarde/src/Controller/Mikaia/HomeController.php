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
        // Données du restaurant avec prix harmonisés en Ariary (Ar)
        $plats = [
            [
                'nom' => 'Le Burger Signature Mikaia',
                'categorie' => 'Plat Principal',
                'prix' => '25 000 Ar',
                'desc' => 'Bœuf fermier, cheddar affiné, oignons caramélisés et sauce secrète de la maison.',
                'badge' => 'Coup de cœur',
                'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?auto=format&fit=crop&w=800&q=80'
            ],
            [
                'nom' => 'Salade Burrata & Figues',
                'categorie' => 'Entrée / Fraîcheur',
                'prix' => '18 000 Ar',
                'desc' => 'Burrata crémeuse des Pouilles, figues fraîches de saison, pignons torréfiés.',
                'badge' => 'De saison',
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=800&q=80'
            ],
            [
                'nom' => 'Le Fondant Chocolat-Passion',
                'categorie' => 'Dessert',
                'prix' => '12 000 Ar',
                'desc' => 'Cœur coulant grand cru, coulis exotique et éclat de noisettes caramélisées.',
                'badge' => 'Incontournable',
                'image' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?auto=format&fit=crop&w=800&q=80'
            ],
        ];

        return $this->render('mikaia/home/index.html.twig', [
            'plats' => $plats,
        ]);
    }
}