<?php

declare(strict_types=1);

namespace App\Http\Controllers;

// Models
use App\Http\Requests\StoreProductRequest;
use App\Models\Budget; //Mock
use App\Models\Product;

//
use App\Services\CalculateContext;
use App\Services\ProductCalculate;

// Rules Strategy Calculate
use App\Services\Strategies\ProgressiveDiscountByQuantity;
use App\Services\Strategies\DiscountPremiumClientStrategy;
use App\Services\Strategies\HeavyWeightFreightTaxStrategy;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    //

    public function index(Request $request)
    {

        $budget = new Budget;
        $baseContext = new CalculateContext($budget);

        $pipeline = new ProductCalculate(
            Collection::make([
                new DiscountPremiumClientStrategy(),
                new ProgressiveDiscountByQuantity(),
                new HeavyWeightFreightTaxStrategy(1)
            ])
        );

        $price = $pipeline->calculate($baseContext);

        return response()->json([$price], 200);
    }

    public function store(StoreProductRequest $request)
    {
        $post = Product::create($request->validated());

        return response()->json($post->id, 201);
    }
}
