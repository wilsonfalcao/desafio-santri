<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Models\Budget;
use App\Models\Product;

use App\Services\CalculateContext;
use App\Services\Strategies\PricePremiumStrategy;
use App\Services\Strategies\ProgressiveDiscountByQuantity;
use App\Services\ProductCalculate;

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
                new PricePremiumStrategy(),
                new ProgressiveDiscountByQuantity()
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
