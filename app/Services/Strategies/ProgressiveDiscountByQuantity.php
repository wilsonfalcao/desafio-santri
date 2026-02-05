<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Services\ICalculateContext;

class ProgressiveDiscountByQuantity implements IStrategy
{
    protected object $discountLevels;

    public function __construct()
    {
        $this->discountLevels = (object) [
            'level1' => 0,
            'level2' => 3,
            'level3' => 5,
        ];
    }

    // Desconto progressivo: 1-9 unidades (0%), 10-49 (3%), 50+ (5%)
    public function apply(float $basePrice, ICalculateContext $baseValue): float
    {
        return match (true) {
            ($baseValue->getQuantity() <= 9) => ($basePrice * (1 - ($this->discountLevels->level1))),
            ($baseValue->getQuantity() >= 10 && $baseValue->getQuantity() <= 49) => ($basePrice * (1 - ($this->discountLevels->level2 / 100))),
            ($baseValue->getQuantity() > 50) => ($basePrice * (1 - ($this->discountLevels->level3 / 100))),
            false => ($basePrice)
        };
    }
}
