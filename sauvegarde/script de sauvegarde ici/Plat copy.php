<?php

namespace App\Entity; // Définit l'espace de nommage de la classe suivant la norme PSR-4

use App\Repository\PlatRepository; // Importe le dépôt associé pour exécuter des requêtes SQL
use Doctrine\DBAL\Types\Types; // Importe les types de données de Doctrine (ex: TEXT, DATETIME)
use Doctrine\ORM\Mapping as ORM; // Importe l'alias d'attributs ORM pour la cartographie base de données

#[ORM\Entity(repositoryClass: PlatRepository::class)] // Signale à Doctrine que cette classe est une entité SQL liée à son Repository
class Plat
{
    #[ORM\Id] // Définit ce champ comme clé primaire (Primary Key)
    #[ORM\GeneratedValue] // Active l'auto-incrémentation automatique (AUTO_INCREMENT)
    #[ORM\Column] // Mappe la propriété en colonne SQL de type INT
    private ?int $id = null; // Propriété privée encapsulée, nullable au moment de l'instanciation

    #[ORM\Column(length: 255)] // Mappe en VARCHAR(255) obligatoire (NOT NULL)
    private ?string $name = null; // Nom du plat

    #[ORM\Column(type: Types::TEXT, nullable: true)] // Mappe en type TEXT SQL pour des descriptions longues, optionnel
    private ?string $description = null; // Description du plat

    #[ORM\Column] // Mappe en colonne INT SQL obligatoire
    private ?int $price = null; // Prix en Ariary (ex: 25000)

    #[ORM\Column(length: 255, nullable: true)] // Mappe en VARCHAR(255) optionnel
    private ?string $category = null; // Catégorie (ex: Entrée, Plat Principal)

    #[ORM\Column(length: 100, nullable: true)] // Mappe en VARCHAR(100) optionnel
    private ?string $badge = null; // Badge de mise en avant (ex: Coup de cœur)

    #[ORM\Column(length: 255, nullable: true)] // Mappe en VARCHAR(255) optionnel
    private ?string $imageUrl = null; // URL vers l'image d'illustration du plat

    // --- GETTERS & SETTERS (Contrôle d'accès et d'encapsulation des données) ---

    public function getId(): ?int 
    { 
        return $this->id; // Retourne l'identifiant unique généré par la base de données
    }

    public function getName(): ?string 
    { 
        return $this->name; 
    }

    public function setName(string $name): static 
    { 
        $this->name = $name; 
        return $this; // Fluent interface : permet de chaîner les appels (ex: $plat->setName()->setPrice())
    }

    public function getDescription(): ?string 
    { 
        return $this->description; 
    }

    public function setDescription(?string $description): static 
    { 
        $this->description = $description; 
        return $this; 
    }

    public function getPrice(): ?int 
    { 
        return $this->price; 
    }

    public function setPrice(int $price): static 
    { 
        $this->price = $price; 
        return $this; 
    }

    public function getCategory(): ?string 
    { 
        return $this->category; 
    }

    public function setCategory(?string $category): static 
    { 
        $this->category = $category; 
        return $this; 
    }

    public function getBadge(): ?string 
    { 
        return $this->badge; 
    }

    public function setBadge(?string $badge): static 
    { 
        $this->badge = $badge; 
        return $this; 
    }

    public function getImageUrl(): ?string 
    { 
        return $this->imageUrl; 
    }

    public function setImageUrl(?string $imageUrl): static 
    { 
        $this->imageUrl = $imageUrl; 
        return $this; 
    }
}