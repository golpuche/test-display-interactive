<?php

declare(strict_types=1);

namespace Tests\Unit\Application\Handler;

use App\Application\Dto\Response\CustomerResponse;
use App\Application\Handler\GetCustomersHandler;
use App\Application\Query\GetCustomersQuery;
use App\Domain\Entity\Customer;
use App\Domain\Port\CustomerRepositoryInterface;
use App\Domain\ValueObject\Enum\CustomerTitle;
use PHPUnit\Framework\TestCase;

final class GetCustomersHandlerTest extends TestCase
{
    private CustomerRepositoryInterface $customerRepository;
    private GetCustomersHandler $handler;

    protected function setUp(): void
    {
        $this->customerRepository = $this->createMock(CustomerRepositoryInterface::class);

        $this->handler = new GetCustomersHandler(
            $this->customerRepository,
        );
    }

    public function testInvokeSuccess(): void
    {
        $query = new GetCustomersQuery();

        $customer = $this->createMock(Customer::class);
        $customer->method('getTitle')->willReturn(CustomerTitle::M);

        $this->customerRepository->method('findAll')->willReturn([$customer]);

        $customerResponse = CustomerResponse::fromEntity($customer);

        $result = ($this->handler)($query);

        $this->assertCount(1, $result);
        $this->assertEquals([$customerResponse], $result);
    }
}
