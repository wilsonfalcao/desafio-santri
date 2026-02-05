<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Services\ICalculateContext;
use OutOfRangeException;

class HeavyWeightFreightTaxStrategy implements IStrategy
{
    public function __construct(
        protected int $startHeavyWeightGrams = 50000,
        protected float $taxHeavyWeight = 15
    ) {}

    // ○​ Produtos > 50kg têm acréscimo de R$ 15,00
    public function apply(float $currentPrice, ICalculateContext $context): float
    {
        if ($context->getWeightTotal() === 0) {
            throw new OutOfRangeException('Product item must be range of 1-99999999 grams...');
        }

        return match (true) {
            $context->getWeightTotal() > $this->startHeavyWeightGrams => ($currentPrice + $this->taxHeavyWeight),
            $context->getWeightTotal() <= $this->startHeavyWeightGrams => ($currentPrice)
        };
    }
}
