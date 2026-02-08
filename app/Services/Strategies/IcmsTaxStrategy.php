<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Services\ICalculateContext;
use App\ValueObject\Money;
use App\ValueObject\Percentage;

class IcmsTaxStrategy implements IStrategy
{
    // ○​ Impostos variam por estado (SP: 18%, RJ: 20%, etc.) Obs: Sobre o valor da NF e entre estados
    public function apply(float $currentPrice, ICalculateContext $context): float
    {
        $money = Money::fromFloat($currentPrice);
        $percent = Percentage::fromInt($context->getIcmsTax());

        return (float) $percent->addFrom($money->getValue())->toFloat();
    }
}
