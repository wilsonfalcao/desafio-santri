<?php

declare(strict_types=1);

namespace App\ValueObject;

use InvalidArgumentException;

final class Money
{
    private const FACTOR = 100000;
    private readonly int $value;
    /** 
     * @param int $value Percentage int to Fixed Point Aritimetic 
     */
    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public static function fromFloat(float $value): self
    {

        if (!is_finite($value) || !is_float($value)) throw new InvalidArgumentException('Money value must be float valid');

        $percentInt = (int) ($value * self::FACTOR);

        return new self($percentInt);
    }

    public static function fromInt(int $value): self
    {
        if ($value < PHP_INT_MIN && $value > PHP_INT_MAX) throw new InvalidArgumentException(sprintf('Money value must be between %s and %s', PHP_INT_MIN, PHP_INT_MAX,));

        return new self($value);
    }

    public function toFloat(): float
    {
        return $this->value / self::FACTOR;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
