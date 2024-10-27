<?php

declare(strict_types=1);

namespace App\Domain\Exception;

use App\Domain\ValueObject\Id\CustomerId;

final class CustomerNotFoundException extends \Exception
{
    public function __construct(CustomerId $customerId)
    {
        parent::__construct(\sprintf('Customer not found for id %s', $customerId));
    }
}
