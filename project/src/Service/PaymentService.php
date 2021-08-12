<?php

namespace App\Service;

use \Stripe\StripeClient;

class PaymentService 
{
    private $stripe;
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->stripe = new StripeClient('sk_test_sxXoJlwW9YsNIPH6k4vBet4R00vQCcVOT5');
    }

    // function create une session de paiement stripe
    public function create(): string
    {
        // 1. success URL
        // http://localhost:8000/payment/success/ojfijdlsnfdsfjpidsnfkdsfojdsfj
        $protocol = 'http';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'])
        {
            $protocol = 'https';
        }
        $serverName = $_SERVER['SERVER_NAME'];
        $successUrl = $protocol . '://' . $serverName . '/payment/success/{CHECKOUT_SESSION_ID}';

        // 2. cancel URL
        // http://localhost:8000/payment/failure/ojfijdlsnfdsfjpidsnfkdsfojdsfj
        $cancelUrl = $protocol . '://' . $serverName . '/payment/failure/{CHECKOUT_SESSION_ID}';

        // 3. Elements (détail du panier)
        /**
         * 1 item : (array associatif)
         *  amount : le prix de l'article (float)
         *  quantity : la quantite de l'artcile (int)
         *  currency : 'eur' (string)
         *  name : le nom de l'article (string)
         */

        $items = []; // (array de array associatif)
        $panier = $this->cartService->get();
        foreach ($panier['elements'] as $element)
        {
            // $element :
            /*
            [
                'book' => $book,
                'quantity' => 2
            ]
            */
            $item = [
                'amount' => $element['book']->getPrice() * 100,
                'quantity' => $element['quantity'],
                'currency' => 'eur',
                'name' => $element['book']->getTitle()
            ];

            // array_push($items, $item);
            $items[] = $item;
        }

        $session = $this->stripe->checkout->sessions->create([
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => $items
        ]);

        return $session->id;
    }
}