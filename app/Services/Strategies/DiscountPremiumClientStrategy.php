<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Services\ICalculateContext;

class DiscountPremiumClientStrategy implements IStrategy
{
    public function __construct(
        protected int $premiumDiscount = 2
    ) {}

    // ○​ Cliente "Premium" recebe 2% adicional
    public function apply(float $basePrice, ICalculateContext $baseValue): float
    {
        return match ($baseValue->isClientPremium()) {
            true => ($basePrice * (1 - ($this->premiumDiscount / 100))),
            false => ($basePrice)
        };
    }
}
