<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Enum\Currency;
use App\Domain\ValueObject\Id\OrderId;

class Order
{
    private function __construct(
        private readonly OrderId $id,
        private readonly Customer $customer,
        private readonly int $productId,
        private readonly int $quantity,
        private readonly float $price,
        private readonly Currency $currency,
        private readonly \DateTimeImmutable $date,
    ) {
    }

    public static function create(
        OrderId $id,
        Customer $customer,
        int $productId,
        int $quantity,
        float $price,
        Currency $currency,
        \DateTimeImmutable $date,
    ): self {
        return new self(
            id: $id,
            customer: $customer,
            productId: $productId,
            quantity: $quantity,
            price: $price,
            currency: $currency,
            date: $date
        );
    }

    public function getId(): OrderId
    {
        return $this->id;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }
}
