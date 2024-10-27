<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObject\Id;

use App\Domain\ValueObject\Id\CustomerId;
use PHPUnit\Framework\TestCase;

final class CustomerIdTest extends TestCase
{
    public function testGetId(): void
    {
        $customerId = new CustomerId(2);

        self::assertSame(2, $customerId->getId());
    }
}
