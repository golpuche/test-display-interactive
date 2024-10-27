<?php

declare(strict_types=1);

namespace App\Domain\Port;

use App\Domain\Entity\Customer;
use App\Domain\Exception\CustomerNotFoundException;
use App\Domain\ValueObject\Id\CustomerId;

interface CustomerRepositoryInterface
{
    public function findAll(): array;

    /** @throws CustomerNotFoundException */
    public function findById(CustomerId $customerId): Customer;

    public function save(Customer $customer): void;
}
