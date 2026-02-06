<?php

namespace Tests\Unit\Services\Strategies;

use App\Services\ICalculateContext;
use App\Services\Strategies\DiscountPremiumClientStrategy;
use Mockery;
use PHPUnit\Framework\TestCase;

class DiscountPremiumClientStrategyTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_apply_discount_client_premium_correctly(): void
    {
        $context = Mockery::mock(ICalculateContext::class);
        $context->shouldReceive('isClientPremium')->andReturn(true);

        $percentPremiumClient = 2;
        $discountPremiumClientStrategy =  new DiscountPremiumClientStrategy($percentPremiumClient);
        $baseFloatPrice = 1000;

        $result = $discountPremiumClientStrategy->apply($baseFloatPrice, $context);

        $this->assertEquals(980, $result);
        $this->assertIsFloat($result);
    }
}
