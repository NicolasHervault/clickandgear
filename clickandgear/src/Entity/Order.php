<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use App\Entity\Utilisateur; // Change "Utilisateur" par "User" si nécessaire
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'string', length: 255)]
    private $status;

    #[ORM\ManyToOne(targetEntity: Utilisateur::class, inversedBy: 'orders')] // Change "Utilisateur" par "User" si nécessaire
    private $user;

    #[ORM\OneToMany(targetEntity: Cart::class, mappedBy: 'order', cascade: ['persist', 'remove'])]
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->status = 'en cours';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
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

    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Cart $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setOrder($this);
        }
        return $this;
    }
}
