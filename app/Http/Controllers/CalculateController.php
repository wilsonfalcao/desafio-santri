<?php

declare(strict_types=1);

namespace App\Http\Controllers;

// Mock
use App\Models\BudgetBuild;
use App\Models\BudgetMock;
// Calculate Service
use App\Services\CalculateContext;
use App\Services\PricingService;
// Strategy Calc
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CalculateController extends Controller
{
    public function store(PricingService $service, Request $request)
    {
        $budget = BudgetBuild::fromJson($request->all());
        // $budget = new BudgetMock();

        $baseContext = new CalculateContext($budget);

        if ($cachePrice = Cache::get($budget->getId())) {
            return $this->createJsonResponse($budget->getId(), $cachePrice);
        }

        $price = $service->calculatePrice($baseContext);

        Cache::put($budget->getId(), $price, now()->addHours(24));

        return $this->createJsonResponse($budget->getId(), $price);
    }

    private function createJsonResponse($id, $price): JsonResponse
    {
        return response()->json([
            'id' => $id,
            'total' => $price,
        ], Response::HTTP_OK);
    }
}
