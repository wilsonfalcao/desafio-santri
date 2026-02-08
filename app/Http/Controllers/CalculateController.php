<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\DTO\Budget\BudgetBuild;
use Illuminate\Support\Facades\Cache;
use App\Services\CalculateContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Services\PricingService;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\StoreCalculateRequest;

class CalculateController extends Controller
{
    //
    public function calculate(PricingService $service, StoreCalculateRequest $request): JsonResponse
    {
        $budget = BudgetBuild::fromJson($request->all());
        // $budget = new BudgetMock();

        $baseContext = new CalculateContext($budget);

        $cachePrice = Cache::get($budget->getId());
        if ($cachePrice) {
            return $this->createJsonResponse($budget->getId(), $cachePrice);
        }

        $price = $service->calculatePrice($baseContext);

        Cache::put($budget->getId(), $price, now()->addMinute(5));

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
