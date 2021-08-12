<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment/success/{stripeSessionId}", name="payment_success")
     */
    public function success(string $stripeSessionId): Response
    {
        return $this->render('payment/success.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }

    /**
     * @Route("/payment/failure/{stripeSessionId}", name="payment_failure")
     */
    public function failure(string $stripeSessionId): Response
    {
        return $this->render('payment/failure.html.twig', [
            'controller_name' => 'PaymentController',
        ]);
    }
}
