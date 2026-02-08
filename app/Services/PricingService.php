<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Strategies\DiscountPremiumClientStrategy;
use App\Services\Strategies\DiscountPriceByClientTypeStrategy;
use App\Services\Strategies\HeavyWeightFreightTaxStrategy;
use App\Services\Strategies\IcmsTaxStrategy;
use App\Services\Strategies\ProgressiveDiscountByQuantity;
use Illuminate\Support\Collection;

class PricingService
{
    public function calculatePrice(ICalculateContext $context): float
    {

        $pipeline = new ProductCalculator(
            Collection::make([
                new DiscountPriceByClientTypeStrategy,
                new DiscountPremiumClientStrategy,
                new ProgressiveDiscountByQuantity,
                new HeavyWeightFreightTaxStrategy(50000),
                new IcmsTaxStrategy,
            ])
        );

        return $pipeline->calculate($context);
    }
}
