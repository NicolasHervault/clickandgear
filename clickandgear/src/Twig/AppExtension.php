<?php
// src/Twig/AppExtension.php
namespace App\Twig;

use App\Repository\CartRepository;
use Symfony\Bundle\SecurityBundle\Security; // Assure-toi que cet import est correct pour utiliser security.helper
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $cartRepository;
    private $security;

    public function __construct(CartRepository $cartRepository, Security $security)
    {
        $this->cartRepository = $cartRepository;
        $this->security = $security;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('cart_count', [$this, 'getCartCount']),
        ];
    }

    public function getCartCount(): int
    {
        $user = $this->security->getUser();
        return $user ? $this->cartRepository->getTotalItemsCount($user) : 0;
    }
}
