<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Services\ICalculateContext;
use App\ValueObject\Money;
use App\ValueObject\Percentage;

class ProgressiveDiscountByQuantity implements IStrategy
{
    protected object $discountLevels;

    public function __construct()
    {
        $this->discountLevels = (object) [
            'level1' => 0,
            'level2' => 3000,
            'level3' => 5000,
        ];
    }

    // Desconto progressivo: 1-9 unidades (0%), 10-49 (3%), 50+ (5%)
    public function apply(float $basePrice, ICalculateContext $baseValue): float
    {
        $progressivePercent = match (true) {
            ($baseValue->getQuantity() <= 9) => $this->discountLevels->level1,
            ($baseValue->getQuantity() >= 10 && $baseValue->getQuantity() <= 49) => $this->discountLevels->level2,
            ($baseValue->getQuantity() >= 50) => $this->discountLevels->level3,
            default => ($basePrice)
        };

        $money = Money::fromFloat($basePrice);
        $percent = Percentage::fromInt($progressivePercent);

        return $percent->substractFrom($money->getValue())->toFloat();
    }
}
