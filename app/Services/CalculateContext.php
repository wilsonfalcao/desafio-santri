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
    /**
     * @param IBudget $budget Budget Instance
     * @param int $profitDiscount Discount to Apply Product Before All Stratagies
     * Example: 10 = 10% | 1 = 1% | 25,52% = 2552
     */
    public function __construct(
        protected IBudget $budget,
        protected int $profitDiscount = 0
    ) {}

    public function getTotal(): float
    {
        return $this->budget->getProduct()->price * (1 - ($this->profitDiscount / 100));
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
