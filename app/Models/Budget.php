<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\IBudget;

class Budget implements IBudget
{
    public function getId(): string
    {
        return '';
    }

    public function getProduct(): Product
    {
        return Product::find(1);
    }

    public function getUser(): User
    {
        return User::find(1);
    }

    public function getQuantity(): int
    {
        return 10;
    }
}
