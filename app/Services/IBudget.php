<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\User;

interface IBudget
{
    public function getProduct(): Product;

    public function getUser(): User;

    public function getQuantity(): int;
}
