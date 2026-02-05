<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\IBudget;

use function Symfony\Component\Clock\now;

class Budget implements IBudget
{
    protected string $id;
    public function __construct()
    {
        $this->id = hash('md5', date("h:i:sa"));
    }
    public function getId(): string
    {
        return $this->id;
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
