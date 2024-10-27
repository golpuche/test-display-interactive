<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Domain\Entity\Customer;
use App\Domain\Port\CustomerRepositoryInterface;
use App\Domain\ValueObject\Enum\CustomerTitle;
use App\Domain\ValueObject\Id\CustomerId;

final class CustomerPersister
{
    public const CUSTOMER_DATA_ID = 0;
    private const CUSTOMER_DATA_TITLE = 1;
    private const CUSTOMER_DATA_LASTNAME = 2;
    private const CUSTOMER_DATA_FIRSTNAME = 3;
    private const CUSTOMER_DATA_POSTAL_CODE = 4;
    private const CUSTOMER_DATA_CITY = 5;
    private const CUSTOMER_DATA_EMAIL = 6;

    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository,
    ) {
    }

    public function persist(array $data): Customer
    {
        $customer = Customer::create(
            id: new CustomerId((int) $data[self::CUSTOMER_DATA_ID]),
            title: CustomerTitle::from($data[self::CUSTOMER_DATA_TITLE]),
            lastname: array_key_exists(self::CUSTOMER_DATA_LASTNAME, $data) && '' !== $data[self::CUSTOMER_DATA_LASTNAME] ? $data[self::CUSTOMER_DATA_LASTNAME] : null,
            firstname: array_key_exists(self::CUSTOMER_DATA_FIRSTNAME, $data) && '' !== $data[self::CUSTOMER_DATA_FIRSTNAME] ? $data[self::CUSTOMER_DATA_FIRSTNAME] : null,
            postalCode: array_key_exists(self::CUSTOMER_DATA_POSTAL_CODE, $data) && '' !== $data[self::CUSTOMER_DATA_POSTAL_CODE] ? $data[self::CUSTOMER_DATA_POSTAL_CODE] : null,
            city: array_key_exists(self::CUSTOMER_DATA_CITY, $data) && '' !== $data[self::CUSTOMER_DATA_CITY] ? $data[self::CUSTOMER_DATA_CITY] : null,
            email: array_key_exists(self::CUSTOMER_DATA_EMAIL, $data) && '' !== $data[self::CUSTOMER_DATA_EMAIL] ? $data[self::CUSTOMER_DATA_EMAIL] : null,
        );

        $this->customerRepository->save($customer);

        return $customer;
    }
}
