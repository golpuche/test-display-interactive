<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\ValueObject\Id;

use App\Domain\ValueObject\Id\OrderId;
use PHPUnit\Framework\TestCase;

final class OrderIdTest extends TestCase
{
    public function testGetId(): void
    {
        $orderId = new OrderId('test');

        self::assertSame('test', $orderId->getId());
    }
}
