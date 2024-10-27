<?php

declare(strict_types=1);

namespace App\Domain\ValueObject\Id;

class CustomerId
{
    public function __construct(private readonly int $id)
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }
}
