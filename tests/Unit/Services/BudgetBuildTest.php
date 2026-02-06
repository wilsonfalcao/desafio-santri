<?php

namespace Tests\Unit\Services;

use App\Models\BudgetBuild;
use App\Models\Product;
use Mockery;
use PHPUnit\Framework\TestCase;

class BudgetBuildTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_build_from_json_static_function_correctly(): void
    {
        $arrayJson = [
            'id' => 'teste',
            'product' => [
                ['id' => 1, 'quantity' => 2]
            ],
            'user_id' => 1
        ];

        $budgetMock = BudgetBuild::fromJson($arrayJson);

        $this->assertInstanceOf(BudgetBuild::class, $budgetMock);
    }

    public function test_product_access_from_budget_build_behavior_correctly(): void
    {

        $productMock = Mockery::mock('alias:App\Models\Product');

        $productMock->shouldReceive('find')
            ->with(1)
            ->andReturn($productMock);

        $arrayJson = [
            'id' => 'teste',
            'product' => [
                ['id' => 1, 'quantity' => 2]
            ],
            'user_id' => 1
        ];

        $budgetMock = BudgetBuild::fromJson($arrayJson);
        $result = $budgetMock->getProduct();

        $this->assertInstanceOf(Product::class, $result);
    }
}
