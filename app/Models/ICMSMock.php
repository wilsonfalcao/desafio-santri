<?php

declare(strict_types=1);

namespace App\Models;

use App\ValueObject\Percentage;
use InvalidArgumentException;

class ICMSMock
{
    /**
     * Example: 18000 = 18% 
     */
    protected array $taxUfMatrix = [
        'SP' => ['PE' => 18000, 'RJ' => 12000, 'MG' => 12000, 'SP' => 0],
        'PE' => ['SP' => 12000, 'RJ' => 12000, 'PE' => 5000,  'MG' => 12000],
        'RJ' => ['SP' => 12000, 'PE' => 12000, 'RJ' => 0,  'MG' => 12000],
        'MG' => ['SP' => 12000, 'PE' => 12000, 'RJ' => 12000, 'MG' => 0],
        'GO' => ['SP' => 18000, 'RJ' => 20000, 'PE' => 12000, 'GO' => 0],
    ];

    protected string $keyUfOrigin;

    public function __construct(string $origin)
    {

        if (! array_key_exists($origin, $this->taxUfMatrix)) {
            throw new InvalidArgumentException('UF origin does not exist in the array.');
        }
        $this->keyUfOrigin = $origin;
    }

    public function getTaxUf(string $destination): int
    {

        if (! array_key_exists($destination, $this->taxUfMatrix[$this->keyUfOrigin])) {
            throw new InvalidArgumentException('UF destination does not exist in the array.');
        }

        $percent = Percentage::fromInt($this->taxUfMatrix[$this->keyUfOrigin][$destination]);

        return $percent->getValue();
    }
}
