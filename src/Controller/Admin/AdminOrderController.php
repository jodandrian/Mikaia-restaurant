<?php

// Définition de l'espace de noms pour les contrôleurs d'administration (Backoffice)
namespace App\Controller\Admin;

// Importation de l'entité Commande
use App\Entity\Order;
// Importation du gestionnaire d'entités de Doctrine
use Doctrine\ORM\EntityManagerInterface;
// Importation de la classe de base Symfony pour les contrôleurs
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// Importation de la classe pour capturer la requête HTTP
use Symfony\Component\HttpFoundation\Request;
// Importation de la classe pour générer des réponses HTML
use Symfony\Component\HttpFoundation\Response;
// Importation des attributs de routage
use Symfony\Component\Routing\Attribute\Route;

// Préfixe d'URL pour toutes les routes d'administration des commandes (/admin/order)
#[Route('/admin/order')]
class AdminOrderController extends AbstractController
{
    // Route principale d'affichage de la liste des commandes (/admin/order/)
    #[Route('/', name: 'app_admin_order_index', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        // Récupération de toutes les commandes triées de la plus récente à la plus ancienne
        $orders = $em->getRepository(Order::class)->findBy([], ['createdAt' => 'DESC']);

        // Rendu de la vue Twig admin/order/index.html.twig en lui passant le tableau de commandes
        return $this->render('admin/order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    // Route de mise à jour du statut d'une commande spécifique (/admin/order/{id}/status)
    #[Route('/{id}/status', name: 'app_admin_order_status', methods: ['POST'])]
    public function changeStatus(Order $order, Request $request, EntityManagerInterface $em): Response
    {
        // Récupération de la nouvelle valeur du statut soumise par le formulaire
        $newStatus = $request->request->get('status');

        // Si une valeur de statut est bien fournie
        if ($newStatus) {
            // Modification de l'attribut status de l'entité Order
            $order->setStatus($newStatus);
            // Sauvegarde de la mise à jour (UPDATE) en base de données
            $em->flush();
            // Notification Flash de confirmation pour l'administrateur
            $this->addFlash('success', 'Le statut de la commande a été mis à jour.');
        }

        // Redirection vers la page principale de gestion des commandes
        return $this->redirectToRoute('app_admin_order_index');
    }
}