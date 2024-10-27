<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entity;

use App\Domain\Entity\Customer;
use App\Domain\Entity\Order;
use App\Domain\ValueObject\Enum\Currency;
use App\Domain\ValueObject\Id\OrderId;
use PHPUnit\Framework\TestCase;

final class OrderTest extends TestCase
{
    public function testCreate(): void
    {
        $order = Order::create(
            id: new OrderId('1/01'),
            customer: $customer = $this->createMock(Customer::class),
            productId: 200,
            quantity: 20,
            price: 30,
            currency: Currency::EUR,
            date: new \DateTimeImmutable('2024-01-01'),
        );

        self::assertInstanceOf(Order::class, $order);
        self::assertEquals(new OrderId('1/01'), $order->getId());
        self::assertSame(200, $order->getProductId());
        self::assertSame(20, $order->getQuantity());
        self::assertSame(30.0, $order->getPrice());
        self::assertSame(Currency::EUR, $order->getCurrency());
        self::assertSame($customer, $order->getCustomer());
        self::assertEquals(new \DateTimeImmutable('2024-01-01'), $order->getDate());
    }
}
