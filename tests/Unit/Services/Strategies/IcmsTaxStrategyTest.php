<?php

namespace Tests\Unit\Services\Strategies;

use App\Services\ICalculateContext;
use App\Services\Strategies\IcmsTaxStrategy;
use Mockery;
use PHPUnit\Framework\TestCase;

class IcmsTaxStrategyTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_apply_icms_tax_correctly(): void
    {
        $context = Mockery::mock(ICalculateContext::class);
        $context->shouldReceive('getIcmsTax')->andReturn(18);

        $discountPremiumClientStrategy =  new IcmsTaxStrategy();
        $baseFloatPrice = 1000;

        $result = $discountPremiumClientStrategy->apply($baseFloatPrice, $context);

        $this->assertEquals(1180, $result);
        $this->assertIsFloat($result);
    }
}
