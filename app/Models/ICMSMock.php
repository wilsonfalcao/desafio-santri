<?php

declare(strict_types=1);

namespace App\Models;

use InvalidArgumentException;

class ICMSMock
{

    protected array $taxUfMatrix = [
        'SP' => ['PE' => 18, 'RJ' => 12, 'MG' => 12, 'SP' => 0],
        'PE' => ['SP' => 12, 'RJ' => 12, 'PE' => 5,  'MG' => 12],
        'RJ' => ['SP' => 12, 'PE' => 12, 'RJ' => 0,  'MG' => 12],
        'MG' => ['SP' => 12, 'PE' => 12, 'RJ' => 12, 'MG' => 0],
        'GO' => ['SP' => 18, 'RJ' => 20, 'PE' => 12, 'GO' => 0],
    ];

    protected string $keyUfOrigin;

    public function __construct(string $origin)
    {

        if (!array_key_exists($origin, $this->taxUfMatrix)) throw new InvalidArgumentException('UF origin does not exist in the array.');
        $this->keyUfOrigin = $origin;
    }

    public function getTaxUf(string $destination): int
    {

        if (!array_key_exists($destination, $this->taxUfMatrix[$this->keyUfOrigin])) throw new InvalidArgumentException('UF destination does not exist in the array.');
        return $this->taxUfMatrix[$this->keyUfOrigin][$destination];
    }
}
