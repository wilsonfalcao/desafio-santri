<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\IBudget;

class BudgetMock implements IBudget
{
    protected string $id;

    public function __construct()
    {
        $this->id = 'b65b886f173ec05da2e629fcaff7ddd6';
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
