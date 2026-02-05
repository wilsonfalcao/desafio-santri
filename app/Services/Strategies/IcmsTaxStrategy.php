<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Services\ICalculateContext;

class IcmsTaxStrategy implements IStrategy
{
    // ○​ Impostos variam por estado (SP: 18%, RJ: 20%, etc.) Obs: Sobre o valor da NF e entre estados
    public function apply(float $currentPrice, ICalculateContext $context): float
    {
        return (float) ($currentPrice * (1 + ($context->getIcmsTax() / 100)));
    }
}
