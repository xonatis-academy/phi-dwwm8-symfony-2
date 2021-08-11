<?php

namespace App\Service;

use App\Entity\Book;

class BookPricerService
{
    public function computePrice(Book $book): void
    {
        $desc = $book->getDescription();
        $newPrice = strlen($desc);
        $book->setPrice($newPrice);
    }
}
