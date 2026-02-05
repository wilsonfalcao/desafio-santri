<?php

declare(strict_types=1);

namespace App\Services\Strategies;

use App\Services\ICalculateContext;
use App\Enums\Constraints;

class DicountPriceByClientTypeStrategy implements IStrategy
{

    protected object $discountClientType;

    public function __construct()
    {
        $this->discountClientType = (object) [
            Constraints::CLIENT_TYPE_WHOLESALE => 10,
            Constraints::CLIENT_TYPE_RESALLER => 5,
            Constraints::CLIENT_TYPE_RETAIL => 0,
        ];
    }


    //- Descontos por tipo de cliente (varejo, atacado, revendedor)
    public function apply(float $basePrice, ICalculateContext $context): float
    {
        return match (true) {
            $context => ($basePrice * (1 - ($this->premiumDiscount / 100))),
            false => ($basePrice)
        };
    }
}
