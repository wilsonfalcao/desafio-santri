<?php

namespace Tests\Unit\Unit\ValueObject;

use App\ValueObject\Money;
use App\ValueObject\Percentage;
use PHPUnit\Framework\TestCase;

class MoneyTest extends TestCase
{
    public function test_shoud_return_class_from_static_constructor(): void
    {
        $percent = Money::fromInt(1);

        $this->assertInstanceOf(Money::class, $percent);
    }

    public function test_should_return_int_value_when_created_from_float(): void
    {
        $percent = Money::fromFloat(25.2536);

        // 25.2536 * FATOR (100000) = 252536
        $this->assertEquals(2525360, $percent->getValue());
    }

    public function test_should_apply_discount_and_return_money_float(): void
    {
        $moneyIntValue = Money::fromInt(25812336521); // 258.123,36521
        $percent = Percentage::fromInt(2000); // 2%
        $resultSubstraction = $percent->substractFrom($moneyIntValue->getValue()); //  (25812336521 - (25812336521 * 2000))
        $result = Money::fromInt($resultSubstraction->getValue());


        $this->assertEquals(252960.89791, $result->toFloat());
    }

    public function test_shoud_apply_markup_by_percentage_class_and_return_money_int(): void
    {
        $moneyIntValue = Money::fromFloat(258123.36521);
        $percent = Percentage::fromInt(2000); // 2%
        $resultSubstraction = $percent->addFrom($moneyIntValue->getValue()); //  (25812336521 + (25812336521 * 2000))
        $result = Money::fromInt($resultSubstraction->getValue());


        $this->assertEquals(26328583251, $result->getValue());
    }
}
