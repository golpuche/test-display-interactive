<?php

declare(strict_types=1);

namespace App\Application\Handler;

use App\Application\Dto\Response\OrderResponse;
use App\Application\Query\GetCustomerOrdersQuery;
use App\Application\Query\QueryHandlerInterface;
use App\Domain\Entity\Order;
use App\Domain\Exception\CustomerNotFoundException;
use App\Domain\Port\CustomerRepositoryInterface;
use App\Domain\Port\OrderRepositoryInterface;

final class GetCustomerOrdersHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly CustomerRepositoryInterface $customerRepository,
        private readonly OrderRepositoryInterface $orderRepository,
    ) {
    }

    /**
     * @throws CustomerNotFoundException
     */
    public function __invoke(GetCustomerOrdersQuery $query): array
    {
        $customer = $this->customerRepository->findById($query->customerId);

        return array_map(
            fn (Order $order) => OrderResponse::fromEntity($order, $customer->getLastname()),
            $this->orderRepository->findByCustomer($query->customerId)
        );
    }
}
