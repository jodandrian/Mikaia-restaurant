<?php

namespace App\Controller\Front;

use App\Repository\CategoryRepository;
use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_front_home')]
    public function index(DishRepository $dishRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('front/home/index.html.twig', [
            'dishes' => $dishRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }
}