<?php

$cart = [
    'total' => 3.3
];

$_SESSION['cart'] = $cart;

$sessionInterface->set('cart', $cart);

