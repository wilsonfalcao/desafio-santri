<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Services\ICalculateContext;

interface IStrategy
{
    public function apply(float $basePrice, ICalculateContext $baseValue): float;
}
