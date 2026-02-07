<?php

namespace Tests\Unit\Services;

use App\Enums\ClientTypeEnum;
use App\Models\Product;
use App\Models\User;
use App\Services\CalculateContext;
use App\Services\IBudget;
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

    public function test_should_calculate_icms_tax(): void
    {
        $budgetMock = Mockery::mock(IBudget::class);

        $productMock = new Product();
        $productMock->stock_uf = 'PE';

        $userMock = new User();
        $userMock->uf = 'PE';

        $budgetMock->shouldReceive('getProduct')
            ->andReturn($productMock);

        $budgetMock->shouldReceive('getUser')
            ->andReturn($userMock);


        $calculateContextMock = new CalculateContext($budgetMock);
        $result = $calculateContextMock->getIcmsTax();

        $this->assertIsInt($result);
    }

    public function test_should_calculate_get_client_type(): void
    {
        $budgetMock = Mockery::mock(IBudget::class);

        $userMock = new User();
        $userMock->client_type = ClientTypeEnum::WHOLESALE;

        $budgetMock->shouldReceive('getUser')
            ->andReturn($userMock);


        $calculateContextMock = new CalculateContext($budgetMock);
        $result = $calculateContextMock->getClientType();

        $this->assertInstanceOf(ClientTypeEnum::class, $result);
    }

    public function test_should_calculate_get_weight_total(): void
    {
        $budgetMock = Mockery::mock(IBudget::class);

        $productMock = new Product();
        $productMock->weight_grams = 1000;

        $budgetMock->shouldReceive('getProduct')
            ->andReturn($productMock);


        $calculateContextMock = new CalculateContext($budgetMock);
        $result = $calculateContextMock->getWeightTotal();

        $this->assertEquals(1000, $result);
    }
}
