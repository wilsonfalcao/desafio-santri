<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Services\ICalculateContext;
use App\ValueObject\Money;
use App\ValueObject\Percentage;

class DiscountPremiumClientStrategy implements IStrategy
{
    /**
     * @param int $premiumDiscount Premium Client Discount to Apply 
     * Example: 2000 = 2% (default)
     */
    public function __construct(
        protected int $premiumDiscount = 2000
    ) {}

    // ○​ Cliente "Premium" recebe 2% adicional
    public function apply(float $basePrice, ICalculateContext $baseValue): float
    {
        $money = Money::fromFloat($basePrice);
        $percent = Percentage::fromInt($this->premiumDiscount);

        return match ($baseValue->isClientPremium()) {
            true => ($percent->substractFrom($money->getValue())->toFloat()),
            false => ($basePrice)
        };
    }
}
