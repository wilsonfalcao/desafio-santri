<?php

namespace Tests\Unit\Services\Strategies;

use App\Services\ICalculateContext;
use App\Services\Strategies\ProgressiveDiscountByQuantity;
use Mockery;
use PHPUnit\Framework\TestCase;

class ProgressiveDiscountByQuantityStrategyTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_apply_volume_discounts_by_quantity_level1_correctly(): void
    {
        $context = Mockery::mock(ICalculateContext::class);
        $context->shouldReceive('getQuantity')->andReturn(9);

        $discountPremiumClientStrategy =  new ProgressiveDiscountByQuantity();
        $baseFloatPrice = 1000;

        $result = $discountPremiumClientStrategy->apply($baseFloatPrice, $context);

        $this->assertEquals(1000, $result);
        $this->assertIsFloat($result);
    }

    public function test_apply_volume_discounts_by_quantity_level2_correctly(): void
    {
        $context = Mockery::mock(ICalculateContext::class);
        $context->shouldReceive('getQuantity')->andReturn(49);

        $discountPremiumClientStrategy =  new ProgressiveDiscountByQuantity();
        $baseFloatPrice = 1000;

        $result = $discountPremiumClientStrategy->apply($baseFloatPrice, $context);

        $this->assertEquals(970, $result);
        $this->assertIsFloat($result);
    }

    public function test_apply_volume_discounts_by_quantity_level3_correctly(): void
    {
        $context = Mockery::mock(ICalculateContext::class);
        $context->shouldReceive('getQuantity')->andReturn(50);

        $discountPremiumClientStrategy =  new ProgressiveDiscountByQuantity();
        $baseFloatPrice = 1000;

        $result = $discountPremiumClientStrategy->apply($baseFloatPrice, $context);

        $this->assertEquals(950, $result);
        $this->assertIsFloat($result);
    }
}
