<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Dto\Response;

use App\Application\Dto\Response\OrderResponse;
use App\Domain\Entity\Customer;
use App\Domain\Entity\Order;
use App\Domain\ValueObject\Enum\Currency;
use App\Domain\ValueObject\Id\OrderId;
use PHPUnit\Framework\TestCase;

final class OrderResponseTest extends TestCase
{
    public function testFromEntity(): void
    {
        $orderResponse = OrderResponse::fromEntity(Order::create(
            id: new OrderId('1/01'),
            customer: $this->createMock(Customer::class),
            productId: 200,
            quantity: 20,
            price: 30,
            currency: Currency::EUR,
            date: new \DateTimeImmutable('2024-01-01'),
        ), 'Norris');

        self::assertInstanceOf(OrderResponse::class, $orderResponse);
        self::assertSame('Norris', $orderResponse->last_name);
        self::assertSame('1/01', $orderResponse->purchase_identifier);
        self::assertSame(200, $orderResponse->product_id);
        self::assertSame(20, $orderResponse->quantity);
        self::assertSame(30.0, $orderResponse->price);
        self::assertSame(Currency::EUR->name, $orderResponse->currency);
        self::assertSame('01/01/2024', $orderResponse->date);
    }
}
