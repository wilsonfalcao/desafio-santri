<?php

namespace Tests\Unit\Services;

use App\Models\BudgetBuild;
use App\Models\Product;
use App\Models\User;
use Mockery;
use PHPUnit\Framework\TestCase;

class BudgetBuildTest extends TestCase
{
    /**
     * A basic unit test example.
     */

    private array $arrayJson = [
        'id' => 'teste',
        'product' => [
            ['id' => 1, 'quantity' => 2]
        ],
        'user_id' => 1
    ];

    public function test_build_from_json_static_function_correctly(): void
    {

        $budgetMock = BudgetBuild::fromJson($this->arrayJson);

        $this->assertInstanceOf(BudgetBuild::class, $budgetMock);
    }

    public function test_product_access_for_budget_build_correctly(): void
    {

        $productMock = Mockery::mock('alias:App\Models\Product');

        $productMock->shouldReceive('find')
            ->with(1)
            ->andReturn($productMock);

        $budgetMock = BudgetBuild::fromJson($this->arrayJson);
        $result = $budgetMock->getProduct();

        $this->assertInstanceOf(Product::class, $result);
    }

    public function test_user_access_for_budget_build_correctly(): void
    {

        $productMock = Mockery::mock('alias:App\Models\User');

        $productMock->shouldReceive('find')
            ->with(1)
            ->andReturn($productMock);

        $budgetMock = BudgetBuild::fromJson($this->arrayJson);
        $result = $budgetMock->getUser();

        $this->assertInstanceOf(User::class, $result);
    }
}
