<?php

namespace App\Repository; // Espace de nommage du Repository

use App\Entity\Dish; // Entité gérée
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository; // Classe parente fournissant les méthodes CRUD prédéfinies
use Doctrine\Persistence\ManagerRegistry; // Registre de connexion Doctrine

/**
 * @extends ServiceEntityRepository<Plat>
 */
class DishRepository extends ServiceEntityRepository
{
    /**
     * Repository constructor.
     * Passes the registry and entity class to the parent Doctrine repository handler.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dish::class); // Lie ce Repository à l'entité Plat
    }
}