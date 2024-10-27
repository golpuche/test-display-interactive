<?php

declare(strict_types=1);

namespace App\Application\Dto\Response;

use App\Domain\Entity\Order;

final class OrderResponse
{
    private function __construct(
        public string $last_name,
        public string $purchase_identifier,
        public int $product_id,
        public int $quantity,
        public float $price,
        public string $currency,
        public string $date,
    ) {
    }

    public static function fromEntity(Order $order, ?string $customerLastName): self
    {
        return new self(
            last_name: $customerLastName,
            purchase_identifier: $order->getId()->getId(),
            product_id: $order->getProductId(),
            quantity: $order->getQuantity(),
            price: $order->getPrice(),
            currency: $order->getCurrency()->name,
            date: $order->getDate()->format('d/m/Y'),
        );
    }
}
