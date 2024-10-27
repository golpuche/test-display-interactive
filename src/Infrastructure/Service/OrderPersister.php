<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Entity\Customer;
use App\Domain\Entity\Order;
use App\Domain\Port\OrderRepositoryInterface;
use App\Domain\ValueObject\Enum\Currency;
use App\Domain\ValueObject\Id\OrderId;

final class OrderPersister
{
    public const ORDER_DATA_CUSTOMER_ID = 1;
    private const ORDER_DATA_ID = 0;
    private const ORDER_DATA_PRODUCT_ID = 2;
    private const ORDER_DATA_QUANTITY = 3;
    private const ORDER_DATA_PRICE = 4;
    private const ORDER_DATA_CURRENCY = 5;
    private const ORDER_DATA_DATE = 6;

    public function __construct(
        private readonly OrderRepositoryInterface $orderRepository,
    ) {
    }

    public function persist(array $data, Customer $customer): void
    {
        $order = Order::create(
            id: new OrderId($data[self::ORDER_DATA_ID]),
            customer: $customer,
            productId: (int) $data[self::ORDER_DATA_PRODUCT_ID],
            quantity: (int) $data[self::ORDER_DATA_QUANTITY],
            price: (float) $data[self::ORDER_DATA_PRICE],
            currency: Currency::from($data[self::ORDER_DATA_CURRENCY]),
            date: new \DateTimeImmutable($data[self::ORDER_DATA_DATE]),
        );

        $this->orderRepository->save($order);
    }
}
