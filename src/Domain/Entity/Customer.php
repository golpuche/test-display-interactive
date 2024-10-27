<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use App\Domain\ValueObject\Enum\CustomerTitle;
use App\Domain\ValueObject\Id\CustomerId;

class Customer
{
    private function __construct(
        private readonly CustomerId $id,
        private readonly CustomerTitle $title,
        private readonly ?string $lastname,
        private readonly ?string $firstname,
        private readonly ?string $postalCode,
        private readonly ?string $city,
        private readonly ?string $email,
    ) {
    }

    public static function create(
        CustomerId $id,
        CustomerTitle $title,
        ?string $lastname,
        ?string $firstname,
        ?string $postalCode,
        ?string $city,
        ?string $email,
    ): self {
        return new self(
            id: $id,
            title: $title,
            lastname: $lastname,
            firstname: $firstname,
            postalCode: $postalCode,
            city: $city,
            email: $email,
        );
    }

    public function getId(): CustomerId
    {
        return $this->id;
    }

    public function getTitle(): CustomerTitle
    {
        return $this->title;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
