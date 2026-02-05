<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Exception;
use Illuminate\Support\Collection;

interface IBudget
{
    public function getProduct(): Product;

    public function getUser(): User;
}

class Budget implements IBudget
{
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
        return 1;
    }
}

interface ICalculateBaseValues
{

    public function getTotal(): float;

    public function getWeightTotal(): int;
}

class CalculateBaseValues implements ICalculateBaseValues
{

    public function __construct(
        protected Budget $budget,
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
}

interface IProductCalculate
{
    public function calculate(ICalculateBaseValues $baseValue): float;
}

interface IStrategy
{
    public function apply(CalculateBaseValues $baseValue): float;
}

abstract class ProductCalculatorAbstract implements IProductCalculate
{
    protected Collection $strategiesPipeline;
    public function __construct(Collection $strategiesPipeline)
    {
        if (empty($this->strategiesPipeline)) throw new Exception('Construct params needs a strategy police...');
        $this->strategiesPipeline = $strategiesPipeline->ensure(IStrategy::class);
    }
}


class PricePremiumStrategy implements IStrategy
{
    public function __construct(protected int $premiumDiscount = 2)
    {
        $this->premiumDiscount = ($this->premiumDiscount / 100);
    }

    public function apply(CalculateBaseValues $baseValue): float
    {
        return match ($baseValue->isClientPremium()) {
            true => ($baseValue->getTotal() * (1 - $this->premiumDiscount)),
            false => ($baseValue->getTotal())
        };
    }
}


class ProductCalculator extends ProductCalculatorAbstract
{

    public function __construct(array $strategiesPipeline)
    {
        return parent::__construct($strategiesPipeline);
    }

    public function calculate(ICalculateBaseValues $baseValue): float
    {

        $priceCalculate = $this->strategiesPipeline->sum(function ($strategy) use (&$baseValue) {
            return $strategy->apply($baseValue);
        });

        return $priceCalculate;
    }
}
