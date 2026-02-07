<?php

declare(strict_types=1);

namespace Tests\Mocks;

use App\Models\Product;
use App\Models\User;
use App\Services\IBudget;
use Illuminate\Support\Str;

class BudgetMock implements IBudget
{
    protected string $id;

    public function __construct()
    {
        $this->id = Str::uniqid();
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
