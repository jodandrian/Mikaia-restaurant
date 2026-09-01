<?php

namespace App\Controller\Admin;

use App\Repository\CategoryRepository;
use App\Repository\DishRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_admin_dashboard')]
    public function index(DishRepository $dishRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'totalDishes' => $dishRepository->count([]),
            'totalCategories' => $categoryRepository->count([]),
            'recentDishes' => $dishRepository->findBy([], ['id' => 'DESC'], 5),
        ]);
    }
}