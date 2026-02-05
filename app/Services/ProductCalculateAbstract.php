<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Support\Collection;

abstract class ProductCalculatorAbstract implements IProductCalculate
{
    protected Collection $strategiesPipeline;

    public function __construct(Collection $strategiesPipeline)
    {
        if (empty($this->strategiesPipeline)) {
            throw new Exception('Construct params needs a strategy police...');
        }
        $this->strategiesPipeline = $strategiesPipeline->ensure(IStrategy::class);
    }
}
