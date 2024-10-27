<?php

declare(strict_types=1);

namespace App\UI\Controller;

use App\Application\Query\GetCustomersQuery;
use App\Application\Query\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[
    Route('/customers', name: 'get_all_customers', methods: ['GET'])
]
final class GetCustomersController extends AbstractController
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    public function __invoke(): JsonResponse
    {
        $customers = $this->queryBus->ask(new GetCustomersQuery());

        return $this->json($customers, Response::HTTP_OK);
    }
}
