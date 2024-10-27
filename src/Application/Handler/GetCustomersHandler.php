<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Dto\Response\CustomerResponse;
use App\Application\Query\GetCustomersQuery;
use App\Application\Query\QueryHandlerInterface;
use App\Domain\Entity\Customer;
use App\Domain\Port\CustomerRepositoryInterface;

final class GetCustomersHandler implements QueryHandlerInterface
{
    public function __construct(private readonly CustomerRepositoryInterface $customerRepository)
    {
    }

    public function __invoke(GetCustomersQuery $query): array
    {
        return array_map(
            fn (Customer $customer) => CustomerResponse::fromEntity($customer),
            $this->customerRepository->findAll()
        );
    }
}
