<?php

namespace Tests\Unit\Services;

use App\Models\BudgetBuild;
use App\Models\Product;
use App\Services\CalculateContext;
use App\Services\IBudget;
use App\Services\ICalculateContext;
use Mockery;
use PHPUnit\Framework\TestCase;

class CalculateContextTest extends TestCase
{
    /**
     * A basic unit test example.
     */

    public function test_should_calculate_total_with_profitdiscount(): void
    {
        $budgetMock = Mockery::mock(IBudget::class);

        $productMock = new Product();
        $productMock->price = 1000;

        $budgetMock->shouldReceive('getProduct')
            ->andReturn($productMock);


        $calculateContextMock = new CalculateContext($budgetMock, 10);
        $result = $calculateContextMock->getTotal();

        $this->assertEquals(900, $result);
    }
}
