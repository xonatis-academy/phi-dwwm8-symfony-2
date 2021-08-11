<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;


/*
$kikoikoi = [
    'total' => 234.5,
    'elements' => [
        '265' => [
            'book' => $item,
            'quantity' => 2
        ],
        '45' => [
            'book' => $item2,
            'qquantity' => 1
        ],
        '67' => [
            'book' => $item3,
            'quantity' => 2
        ]
    ]
];
    */

class CartService
{
    private $sessionInterface;

    public function __construct(SessionInterface $sessionInterface)
    {
        $this->sessionInterface = $sessionInterface;
    }

    public function get(): array
    {
        $cart = $this->sessionInterface->get('cart');
        if ($cart === null)
        {
            $cart = [
                'total' => 0.0,
                'elements' => []
            ];
        }
        return $cart;
    }

    public function add($item): void
    {
        // 1. On récupère le panier s'il existe, sinon on en prend un nouveau
        $cart = $this->get();

        // 2. On ajoute le book s'il n'y en a pas
        $itemId = $item->getId();
        if (!isset($cart['elements'][$itemId]))
        {
            $cart['elements'][$itemId] = [
                'book' => $item,
                'quantity' => 0
            ];
        }

        // 3. On incrémente la quantity et on recalcule le prix total
        $cart['elements'][$itemId]['quantity'] = $cart['elements'][$itemId]['quantity'] + 1;
        $cart['total'] = $cart['total'] + $item->getPrice();

        // 4. On sauvegarde le nouveau panier
        $this->sessionInterface->set('cart', $cart);
    }

    public function delete($item): void
    {
        // 1. On récupère le panier
        $cart = $this->get();

        // 2. Si le livre n'est pas dans le panier, on ne fait rien
        $itemId = $item->getId();
        if (!isset($cart['elements'][$itemId]))
        {
            return;
        }

        // 3. Il existe, alors on met à jour les quantités
        $cart['total'] = $cart['total'] - $item->getPrice();
        $cart['elements'][$itemId]['quantity'] = $cart['elements'][$itemId]['quantity'] - 1;

        // 4. Si la quantité est de 0, on l'enlève complètement du panier
        if ($cart['elements'][$itemId]['quantity'] <= 0)
        {
            unset($cart['elements'][$itemId]);
        }

        // 5. On sauvegarde le panier
        $this->sessionInterface->set('cart', $cart);
    }

    public function clear()
    {
        $this->sessionInterface->remove('cart');
    }

    public function removeLine($item)
    {
        // 1. On récupère le panier
        $cart = $this->get();

        // 2. Si le livre n'est pas dans le panier, on ne fait rien
        $itemId = $item->getId();
        if (!isset($cart['elements'][$itemId]))
        {
            return;
        }

        // 3. On met à jour le total et on sucre la ligne (sucre = supprimer)
        $cart['total'] = $cart['total'] - $item->getPrice() * $cart['elements'][$itemId]['quantity'];
        unset($cart['elements'][$itemId]);

        // 4. On enregistre le panier
        $this->sessionInterface->set('cart', $cart);
    }
}