<?php

declare(strict_types=1);

namespace App\Application\Dto\Response;

use App\Domain\Entity\Customer;

final class CustomerResponse
{
    private function __construct(
        public int $id,
        public string $title,
        public ?string $lastname,
        public ?string $firstname,
        public ?string $postal_code,
        public ?string $city,
        public ?string $email,
    ) {
    }

    public static function fromEntity(Customer $customer): self
    {
        return new self(
            id: $customer->getId()->getId(),
            title: mb_strtolower($customer->getTitle()->name),
            lastname: $customer->getLastname(),
            firstname: $customer->getFirstname(),
            postal_code: $customer->getPostalCode(),
            city: $customer->getCity(),
            email: $customer->getEmail(),
        );
    }
}
