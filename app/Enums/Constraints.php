<?php

declare(strict_types=1);

namespace App\Enums;

enum Constraints: string
{
    case CLIENT_TYPE_WHOLESALE = 'atacado';
    case CLIENT_TYPE_RETAIL = 'varejo';
    case CLIENT_TYPE_RESALLER = 'revendedor';
}
