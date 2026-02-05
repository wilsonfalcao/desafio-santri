<?php

declare(strict_types=1);

namespace App\Services;

interface ICalculateContext
{
    public function getTotal(): float;

    public function getWeightTotal(): int;

    public function isClientPremium(): bool;

    public function getQuantity(): int;
}


class CalculateContext implements ICalculateContext
{
    public function __construct(
        protected IBudget $budget,
        protected float $profitDiscount = 0
    ) {}

    public function getTotal(): float
    {
        return $this->budget->getProduct()->price * (1 - $this->profitDiscount);
    }

    public function getWeightTotal(): int
    {
        return $this->budget->getProduct()->weight_grams;
    }

    public function isClientPremium(): bool
    {
        return ($this->budget->getUser()->is_premium) ? true : false;
    }

    public function getQuantity(): int
    {
        return $this->budget->getQuantity();
    }
}
