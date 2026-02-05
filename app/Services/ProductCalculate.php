<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Collection;
use InvalidArgumentException;

interface IProductCalculate
{
    public function calculate(ICalculateContext $baseValue): float;
}

class ProductCalculate implements IProductCalculate
{
    protected Collection $strategiesPipeline;

    public function __construct(Collection $strategiesPipeline)
    {
        if ($strategiesPipeline->isEmpty()) {
            throw new InvalidArgumentException('Construct params needs a strategy police...');
        }
        $this->strategiesPipeline = $strategiesPipeline;
    }

    public function calculate(ICalculateContext $baseValue): float
    {

        $priceCalculate = $baseValue->getTotal();

        return $this->strategiesPipeline->reduce(function (float $actualPrice, $strategy) use (&$baseValue) {
            return $strategy->apply($actualPrice, $baseValue);
        }, $priceCalculate);

        return $priceCalculate;
    }
}
