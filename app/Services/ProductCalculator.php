<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Collection;
use InvalidArgumentException;

interface IProductCalculate
{
    public function calculate(ICalculateContext $baseValue): float;
}

class ProductCalculator implements IProductCalculate
{
    protected Collection $strategiesPipeline;

    public function __construct(Collection $strategiesPipeline)
    {
        if ($strategiesPipeline->isEmpty()) {
            throw new InvalidArgumentException('Construct params needs a strategy police...');
        }
        $this->strategiesPipeline = $strategiesPipeline;
    }

    public function calculate(ICalculateContext $calculateContex): float
    {

        $priceCalculate = $calculateContex->getTotal();

        return $this->strategiesPipeline->reduce(function (float $actualPrice, $strategy) use (&$calculateContex) {
            return $strategy->apply($actualPrice, $calculateContex);
        }, $priceCalculate);

        return $priceCalculate;
    }
}
