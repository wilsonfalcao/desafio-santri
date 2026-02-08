<?php

declare(strict_types=1);

namespace App\ValueObject;

use InvalidArgumentException;

final class Percentage
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

        if ($value < 0 && $value <= 100) throw new InvalidArgumentException('Percentage value must be between 0 and 100');

        $percentInt = (int) ($value / self::FACTOR);

        return new self($percentInt);
    }

    public static function fromInt(int $value): self
    {
        if ($value < 0 || $value > (100 * self::FACTOR)) throw new \InvalidArgumentException('Invalid percentage value.');

        return new self($value);
    }

    public function toFloat(): float
    {
        return $this->value / self::FACTOR;
    }

    public function applyTo(int $amount): int
    {
        return (int) ($amount * ($this->value / self::FACTOR));
    }

    public function addToInt(int $amount): int
    {
        return (int) ($amount + $this->applyTo($amount));
    }

    public function addFrom(int $amount): self
    {
        $add = (int) $amount + $this->applyTo($amount);

        return new self($add);
    }

    public function substractToInt(int $amount): int
    {
        return (int) $amount - $this->applyTo($amount);
    }

    public function substractFrom(int $amount): self
    {
        $substract = (int) $amount - $this->applyTo($amount);

        return new self($substract);
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
