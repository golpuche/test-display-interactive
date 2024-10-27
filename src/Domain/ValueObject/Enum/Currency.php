<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Enum;

enum Currency: string
{
    case EUR = 'euros';
    case USD = 'dollars';
}
