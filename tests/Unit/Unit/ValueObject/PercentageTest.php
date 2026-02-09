<?php

namespace Tests\Unit\Unit\ValueObject;

use App\ValueObject\Percentage;
use PHPUnit\Framework\TestCase;

class PercentageTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_shoud_return_class_by_static_constructor(): void
    {
        $percent = Percentage::fromInt(1);

        $this->assertInstanceOf(Percentage::class, $percent);
    }

    public function test_should_return_int_value_when_created_from_float(): void
    {
        $percent = Percentage::fromFloat(25.2536);

        // 25.2536 * FATOR (100000) = 252536
        $this->assertEquals(2525360, $percent->getValue());
    }

    public function test_should_apply_discount_and_return_money_int_and_float(): void
    {
        $moneyIntValue = 25812336521; // 258.123,36521
        $percent = Percentage::fromInt(2000); // 2%
        $result = $percent->substractFrom($moneyIntValue); //  (25812336521 - (25812336521 * 2000))


        $this->assertEquals(25296089791, $result->getValue());

        // 25296089791 / FATOR (10000) = 252960.89791 
        $this->assertEquals(252960.89791, $result->toFloat());
    }

    public function test_should_apply_markup_and_return_money_int_and_float(): void
    {
        $moneyIntValue = 25812336521; // 258.123,36521
        $percent = Percentage::fromInt(2000); // 2%
        $result = $percent->addFrom($moneyIntValue); //  (25812336521 + (25812336521 * 2000))


        $this->assertEquals(26328583251, $result->getValue());

        // 2632858325142 / FATOR (10000) = 263285.83251 
        $this->assertEquals(263285.83251, $result->toFloat());
    }
}
