<?php

declare(strict_types=1);

namespace App\Http\Controllers;

// Mock
use App\Models\Budget;
// Calculate Service
use App\Services\CalculateContext;
use App\Services\ProductCalculate;
// Strategy Calc
use App\Services\Strategies\DiscountPremiumClientStrategy;
use App\Services\Strategies\DiscountPriceByClientTypeStrategy;
use App\Services\Strategies\HeavyWeightFreightTaxStrategy;
use App\Services\Strategies\IcmsTaxStrategy;
use App\Services\Strategies\ProgressiveDiscountByQuantity;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CalculateController extends Controller
{
    public function store(Request $request)
    {
        $budget = new Budget;
        $baseContext = new CalculateContext($budget);

        $pipeline = new ProductCalculate(
            Collection::make([
                new DiscountPriceByClientTypeStrategy(),
                new DiscountPremiumClientStrategy,
                new ProgressiveDiscountByQuantity,
                new HeavyWeightFreightTaxStrategy(1),
                new IcmsTaxStrategy,
            ])
        );

        $price = $pipeline->calculate($baseContext);

        $jsonBuild = [
            'id' => $budget->getId(),
            'total' => $price
        ];

        return response()->json([$jsonBuild], 200);
    }
}
