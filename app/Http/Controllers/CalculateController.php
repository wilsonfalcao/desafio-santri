<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\Budget\BudgetBuild;
use App\Services\CalculateContext;
use App\Services\PricingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class CalculateController extends Controller
{
    public function store(PricingService $service, Request $request): JsonResponse
    {
        $budget = BudgetBuild::fromJson($request->all());
        // $budget = new BudgetMock();

        $baseContext = new CalculateContext($budget);

        $cachePrice = Cache::get($budget->getId());
        if ($cachePrice) {
            return $this->createJsonResponse($budget->getId(), $cachePrice);
        }

        $price = $service->calculatePrice($baseContext);

        Cache::put($budget->getId(), $price, now()->addHours(24));

        return $this->createJsonResponse($budget->getId(), $price);
    }

    private function createJsonResponse(string $id, float|int|string $price): JsonResponse
    {
        return response()->json([
            'id' => $id,
            'total' => $price,
        ], Response::HTTP_OK);
    }
}
