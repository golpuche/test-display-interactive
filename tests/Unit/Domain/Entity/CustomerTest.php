<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Entity;

use App\Domain\Entity\Customer;
use App\Domain\ValueObject\Enum\CustomerTitle;
use App\Domain\ValueObject\Id\CustomerId;
use PHPUnit\Framework\TestCase;

final class CustomerTest extends TestCase
{
    public function testCreate(): void
    {
        $customer = Customer::create(
            id: new CustomerId(1),
            title: CustomerTitle::M,
            lastname: 'Norris',
            firstname: 'Chuck',
            postalCode: '83600',
            city: 'Fréjus',
            email: 'chuck@norris.com',
        );

        self::assertInstanceOf(Customer::class, $customer);
        self::assertEquals(new CustomerId(1), $customer->getId());
        self::assertSame(CustomerTitle::M, $customer->getTitle());
        self::assertSame('Norris', $customer->getLastname());
        self::assertSame('Chuck', $customer->getFirstname());
        self::assertSame('83600', $customer->getPostalCode());
        self::assertSame('Fréjus', $customer->getCity());
        self::assertSame('chuck@norris.com', $customer->getEmail());
    }
}
