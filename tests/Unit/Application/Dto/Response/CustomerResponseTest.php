<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Dto\Response;

use App\Application\Dto\Response\CustomerResponse;
use App\Domain\Entity\Customer;
use App\Domain\ValueObject\Enum\CustomerTitle;
use App\Domain\ValueObject\Id\CustomerId;
use PHPUnit\Framework\TestCase;

final class CustomerResponseTest extends TestCase
{
    public function testFromEntity(): void
    {
        $customerResponse = CustomerResponse::fromEntity(Customer::create(
            id: new CustomerId(1),
            title: CustomerTitle::M,
            lastname: 'Norris',
            firstname: 'Chuck',
            postalCode: '83600',
            city: 'Fréjus',
            email: 'chuck@norris.com',
        ));

        self::assertInstanceOf(CustomerResponse::class, $customerResponse);
        self::assertSame(1, $customerResponse->id);
        self::assertSame('m', $customerResponse->title);
        self::assertSame('Norris', $customerResponse->lastname);
        self::assertSame('Chuck', $customerResponse->firstname);
        self::assertSame('83600', $customerResponse->postal_code);
        self::assertSame('Fréjus', $customerResponse->city);
        self::assertSame('chuck@norris.com', $customerResponse->email);
    }
}
