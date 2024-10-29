<?php

namespace App\Entity;

use App\Repository\CartRepository;
use App\Entity\Utilisateur; // Remplace par "User" si c'est le nom de ton entité utilisateur
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'integer')]
    private $quantity;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'carts')] // Change "Utilisateur" par "User" si nécessaire
    private $user;

    #[ORM\ManyToOne(targetEntity: Accessory::class)] // Si Accessory est un article du panier
    private $accessory;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: 'items')]
    private $order;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getUser(): ?Utilisateur // Change "Utilisateur" par "User" si nécessaire
    {
        return $this->user;
    }

    public function setUser(?Utilisateur $user): self // Change "Utilisateur" par "User" si nécessaire
    {
        $this->user = $user;
        return $this;
    }

    public function getAccessory(): ?Accessory
    {
        return $this->accessory;
    }

    public function setAccessory(?Accessory $accessory): self
    {
        $this->accessory = $accessory;
        return $this;
    }
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;
        return $this;
    }
}
