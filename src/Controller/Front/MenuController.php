<?php

namespace App\Controller\Front;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Repository\CategoryRepository;
use App\Repository\DishRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MenuController extends AbstractController
{
    // Affiche le menu
    #[Route('/menu', name: 'app_front_menu', methods: ['GET'])]
    public function index(DishRepository $dishRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('front/menu/index.html.twig', [
            'dishes' => $dishRepository->findAll(),
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    // Traite le formulaire standard HTML sans AJAX
    #[Route('/menu/order', name: 'app_front_order_place', methods: ['POST'])]
    public function placeOrder(Request $request, DishRepository $dishRepository, EntityManagerInterface $em): Response
    {
        $dishId = $request->request->get('dish_id');
        $customerName = trim($request->request->get('customer_name', ''));
        $tableNumber = trim($request->request->get('table_number', ''));
        $quantity = max(1, (int) $request->request->get('quantity', 1));

        $dish = $dishRepository->find($dishId);

        if (!$dish) {
            $this->addFlash('error', 'Le plat sélectionné n\'existe pas.');
            return $this->redirectToRoute('app_front_menu');
        }

        // Création de la commande
        $order = new Order();
        $order->setCustomerInfo($customerName . ' (' . $tableNumber . ')');
        $order->setDeliveryMode('sur_place');
        $order->setStatus('pending');
        $order->setCreatedAt(new \DateTimeImmutable());

        // Création de la ligne de commande
        $orderItem = new OrderItem();
        $orderItem->setDish($dish);
        $orderItem->setQuantity($quantity);
        
        $unitPrice = (float) $dish->getPrice();
        $orderItem->setPrice((string) $unitPrice);
        $orderItem->setOrderRef($order);

        // Calcul du total
        $totalAmount = $unitPrice * $quantity;
        $order->setTotalAmount($totalAmount);

        $em->persist($orderItem);
        $em->persist($order);
        $em->flush();

        $this->addFlash('success', 'Votre commande pour "' . $dish->getName() . '" a été envoyée avec succès !');

        return $this->redirectToRoute('app_front_menu');
    }
}