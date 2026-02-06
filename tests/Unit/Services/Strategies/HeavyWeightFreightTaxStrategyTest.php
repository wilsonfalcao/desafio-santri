<?php

namespace Tests\Unit\Services\Strategies;

use App\Services\ICalculateContext;
use App\Services\Strategies\HeavyWeightFreightTaxStrategy;
use Mockery;
use PHPUnit\Framework\TestCase;

class HeavyWeightFreightTaxStrategyTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_apply_tax_heavy_weight_products_correctly(): void
    {
        $context = Mockery::mock(ICalculateContext::class);
        $context->shouldReceive('getWeightTotal')->andReturn(1001);

        $startHeavyWeightGrams = (int) 1000;
        $taxHeavyWeight = (float) 20;
        $discountPremiumClientStrategy =  new HeavyWeightFreightTaxStrategy($startHeavyWeightGrams, $taxHeavyWeight);
        $baseFloatPrice = (float) 2511.99;

        $result = $discountPremiumClientStrategy->apply($baseFloatPrice, $context);

        $this->assertEquals(2531.99, $result);
        $this->assertIsFloat($result);
    }
}
