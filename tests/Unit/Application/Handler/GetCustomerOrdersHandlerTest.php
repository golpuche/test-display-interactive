<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handler;

use App\Application\Dto\Response\OrderResponse;
use App\Application\Handler\GetCustomerOrdersHandler;
use App\Application\Query\GetCustomerOrdersQuery;
use App\Domain\Entity\Customer;
use App\Domain\Entity\Order;
use App\Domain\Exception\CustomerNotFoundException;
use App\Domain\Port\CustomerRepositoryInterface;
use App\Domain\Port\OrderRepositoryInterface;
use App\Domain\ValueObject\Enum\Currency;
use App\Domain\ValueObject\Id\CustomerId;
use PHPUnit\Framework\TestCase;

final class GetCustomerOrdersHandlerTest extends TestCase
{
    private CustomerRepositoryInterface $customerRepository;
    private OrderRepositoryInterface $orderRepository;
    private GetCustomerOrdersHandler $handler;

    protected function setUp(): void
    {
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);
        $this->orderRepository = $this->createMock(OrderRepositoryInterface::class);

        $this->handler = new GetCustomerOrdersHandler(
            $this->customerRepository,
            $this->orderRepository
        );
    }

    public function testInvokeSuccess(): void
    {
        $customerId = new CustomerId(1);
        $query = new GetCustomerOrdersQuery($customerId);

        $customer = $this->createMock(Customer::class);
        $customer->method('getLastname')->willReturn('Doe');

        $order1 = $this->createMock(Order::class);
        $order1->method('getCurrency')->willReturn(Currency::EUR);
        $order2 = $this->createMock(Order::class);
        $order2->method('getCurrency')->willReturn(Currency::EUR);

        $this->customerRepository->method('findById')->with($customerId)->willReturn($customer);
        $this->orderRepository->method('findByCustomer')->with($customerId)->willReturn([$order1, $order2]);

        $orderResponse1 = OrderResponse::fromEntity($order1, 'Doe');
        $orderResponse2 = OrderResponse::fromEntity($order2, 'Doe');

        $result = ($this->handler)($query);

        $this->assertCount(2, $result);
        $this->assertEquals([$orderResponse1, $orderResponse2], $result);
    }

    public function testInvokeThrowsCustomerNotFoundException(): void
    {
        $customerId = new CustomerId(1);
        $query = new GetCustomerOrdersQuery($customerId);

        $this->customerRepository
            ->method('findById')
            ->with($customerId)
            ->willThrowException(new CustomerNotFoundException($customerId));

        $this->expectException(CustomerNotFoundException::class);

        $this->handler->__invoke($query);
    }
}
