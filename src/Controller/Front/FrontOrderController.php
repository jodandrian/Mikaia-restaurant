<?php

namespace App\Controller\Front;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Dish;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class FrontOrderController extends AbstractController
{
    #[Route('/api/order/create', name: 'api_order_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data || empty($data['items'])) {
            return new JsonResponse(['message' => 'Panier vide ou invalide.'], 400);
        }

        // 1. Récupération flexible du Nom et des Infos client
        $customerName = trim($data['customerName'] ?? $data['name'] ?? $data['fullname'] ?? '');
        $customerInfo = trim($data['customerInfo'] ?? $data['phone'] ?? $data['address'] ?? '');

        // Nom par défaut si aucun champ n'est saisi dans le formulaire
        $displayName = $customerName ?: 'Client Passage';
        $fullDetails = $customerInfo ? ($displayName . ' (' . $customerInfo . ')') : $displayName;

        $order = new Order();
        $order->setCustomerInfo($fullDetails);
        
        $deliveryMode = $data['deliveryMode'] ?? 'livraison';
        $order->setDeliveryMode($deliveryMode);
        $order->setStatus('pending');
        $order->setCreatedAt(new \DateTimeImmutable());

        $subtotal = 0;

        // 2. Traitement des plats avec nettoyage strict du prix
        foreach ($data['items'] as $item) {
            if (empty($item['id']) || empty($item['quantity'])) {
                continue;
            }

            $dish = $em->getRepository(Dish::class)->find($item['id']);

            if ($dish) {
                $orderItem = new OrderItem();
                $orderItem->setDish($dish);
                
                $quantity = max(1, (int)$item['quantity']);
                $orderItem->setQuantity($quantity);

                // Extraction et nettoyage complet de la chaîne de prix (gestion espaces classique + insécables)
                $rawPrice = (string) $dish->getPrice();
                $cleanPriceStr = preg_replace('/[^\d.]/', '', str_replace(',', '.', $rawPrice));
                $unitPrice = (float) $cleanPriceStr;

                $orderItem->setPrice((string)$unitPrice); 
                $orderItem->setOrderRef($order);

                $subtotal += ($unitPrice * $quantity);

                $em->persist($orderItem);
            }
        }

        if ($subtotal === 0) {
            return new JsonResponse(['message' => 'Erreur de calcul sur le prix des plats.'], 400);
        }

        // 3. Calcul de la livraison et enregistrement
        $isEmport = in_array(strtolower($deliveryMode), ['emport', 'emporter', 'sur place', 'sur_place']);
        $deliveryFee = $isEmport ? 0 : 5000;

        $order->setTotalAmount($subtotal + $deliveryFee);

        $em->persist($order);
        $em->flush();

        return new JsonResponse([
            'status' => 'success',
            'message' => 'Commande enregistrée avec succès',
            'orderId' => $order->getId()
        ], 201);
    }
}