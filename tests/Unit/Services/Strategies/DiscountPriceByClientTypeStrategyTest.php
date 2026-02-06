<?php

namespace Tests\Unit\Services\Strategies;

use App\Enums\ClientTypeEnum;
use App\Services\ICalculateContext;
use App\Services\Strategies\DiscountPriceByClientTypeStrategy;
use Mockery;
use PHPUnit\Framework\TestCase;

class DiscountPriceByClientTypeStrategyTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_apply_discount_price_by_wholesale_client_correctly(): void
    {
        $context = Mockery::mock(ICalculateContext::class);
        $clientTypeEnum = ClientTypeEnum::WHOLESALE;
        $context->shouldReceive('getClientType')->andReturn($clientTypeEnum);

        $discountPremiumClientStrategy =  new DiscountPriceByClientTypeStrategy();
        $baseFloatPrice = 1000;

        $result = $discountPremiumClientStrategy->apply($baseFloatPrice, $context);

        $this->assertEquals(900, $result);
        $this->assertIsFloat($result);
    }


    public function test_apply_discount_price_by_retail_client_correctly(): void
    {
        $context = Mockery::mock(ICalculateContext::class);
        $clientTypeEnum = ClientTypeEnum::RETAIL;
        $context->shouldReceive('getClientType')->andReturn($clientTypeEnum);

        $discountPremiumClientStrategy =  new DiscountPriceByClientTypeStrategy();
        $baseFloatPrice = 1000;

        $result = $discountPremiumClientStrategy->apply($baseFloatPrice, $context);

        $this->assertEquals(1000, $result);
        $this->assertIsFloat($result);
    }

    public function test_apply_discount_price_by_resaller_client_correctly(): void
    {
        $context = Mockery::mock(ICalculateContext::class);
        $clientTypeEnum = ClientTypeEnum::RESALLER;
        $context->shouldReceive('getClientType')->andReturn($clientTypeEnum);

        $discountPremiumClientStrategy =  new DiscountPriceByClientTypeStrategy();
        $baseFloatPrice = 1000;

        $result = $discountPremiumClientStrategy->apply($baseFloatPrice, $context);

        $this->assertEquals(950, $result);
        $this->assertIsFloat($result);
    }
}
