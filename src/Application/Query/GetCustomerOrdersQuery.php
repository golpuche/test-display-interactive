<?php

declare(strict_types=1);

namespace App\Application\Query;

use App\Domain\ValueObject\Id\CustomerId;

final class GetCustomerOrdersQuery implements QueryInterface
{
    public function __construct(public CustomerId $customerId)
    {
    }
}
