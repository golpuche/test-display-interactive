<?php

declare(strict_types=1);

namespace App\Domain\Port;

use App\Domain\Entity\Order;
use App\Domain\ValueObject\Id\CustomerId;

interface OrderRepositoryInterface
{
    public function findByCustomer(CustomerId $customerId): array;

    public function save(Order $order): void;
}
