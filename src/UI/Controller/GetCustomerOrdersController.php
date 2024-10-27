<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Application\Query\GetCustomerOrdersQuery;
use App\Application\Query\QueryBusInterface;
use App\Domain\Exception\CustomerNotFoundException;
use App\Domain\ValueObject\Id\CustomerId;
use App\Infrastructure\ValueResolver\CustomerIdValueResolver;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

#[
    Route('/customers/{customerId}/orders', name: 'get_orders_for_customer', methods: ['GET'])
]
final class GetCustomerOrdersController extends AbstractController
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    public function __invoke(
        #[ValueResolver(CustomerIdValueResolver::VALUE_RESOLVER_ALIAS)]
        CustomerId $customerId,
    ): JsonResponse {
        try {
            $customers = $this->queryBus->ask(new GetCustomerOrdersQuery($customerId));
        } catch (CustomerNotFoundException $exception) {
            return $this->json([
                'errorCode' => Response::HTTP_NOT_FOUND,
                'errorDescription' => $exception->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json($customers, Response::HTTP_OK);
    }
}
