<?php

declare(strict_types=1);

namespace App\Services;

use App\Enums\ClientTypeEnum;
use App\Models\ICMSMock;

interface ICalculateContext
{
    public function getTotal(): float;

    public function getWeightTotal(): int;

    public function isClientPremium(): bool;

    public function getQuantity(): int;

    public function getIcmsTax(): int;

    public function getClientType(): ClientTypeEnum;
}

class CalculateContext implements ICalculateContext
{
    public function __construct(
        protected IBudget $budget,
        protected float $profitDiscount = 0
    ) {}

    public function getTotal(): float
    {
        return $this->budget->getProduct()->price * (1 - $this->profitDiscount);
    }

    public function getWeightTotal(): int
    {
        return $this->budget->getProduct()->weight_grams;
    }

    public function isClientPremium(): bool
    {
        return ($this->budget->getUser()->is_premium) ? true : false;
    }

    public function getQuantity(): int
    {
        return $this->budget->getQuantity();
    }

    public function getClientType(): ClientTypeEnum
    {
        return $this->budget->getUser()->client_type;
    }

    public function getIcmsTax(): int
    {

        $origin = $this->budget->getProduct()->stock_uf;
        $destination = $this->budget->getUser()->uf;

        $icmsTax = new ICMSMock($origin);

        return $icmsTax->getTaxUf($destination);
    }
}
