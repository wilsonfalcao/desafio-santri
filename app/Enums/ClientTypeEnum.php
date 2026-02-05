<?php

declare(strict_types=1);

namespace App\Enums;

enum ClientTypeEnum: string
{
    case WHOLESALE = 'ATACADO';
    case RETAIL = 'VAREJO';
    case RESALLER = 'REVENDEDOR';
}
