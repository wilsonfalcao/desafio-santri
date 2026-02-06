<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Enums\ClientTypeEnum;
use App\Services\ICalculateContext;

class DiscountPriceByClientTypeStrategy implements IStrategy
{
    protected array $discountClientType;

    public function __construct()
    {
        $this->discountClientType = [
            ClientTypeEnum::WHOLESALE->value => 10,
            ClientTypeEnum::RESALLER->value => 5,
            ClientTypeEnum::RETAIL->value => 0,
        ];
    }

    // - Descontos por tipo de cliente (varejo, atacado, revendedor)
    public function apply(float $currentPrice, ICalculateContext $context): float
    {

        $discountPercentage = match ($context->getClientType()) {
            ClientTypeEnum::WHOLESALE => $this->discountClientType['ATACADO'],
            ClientTypeEnum::RESALLER => $this->discountClientType['REVENDEDOR'],
            default => $this->discountClientType['VAREJO'],
        };

        if ($discountPercentage === 0) {
            return $currentPrice;
        }

        return $currentPrice * (1 - ($discountPercentage / 100));
    }
}
