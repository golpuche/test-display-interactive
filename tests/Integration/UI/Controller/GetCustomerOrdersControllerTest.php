<?php

declare(strict_types=1);

namespace Tests\Integration\UI\Controller;

use App\Domain\ValueObject\Enum\Currency;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Tests\Integration\Fixture\CustomerFixture;
use Tests\Integration\Fixture\OrderFixture;

class GetCustomerOrdersControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $ormExecutor = new ORMExecutor($entityManager, new ORMPurger());
        $ormExecutor->purge();
        $ormExecutor->execute([new CustomerFixture(), new OrderFixture()]);
    }

    public function testItSuccessToGetCustomerOrders(): void
    {
        $this->client->request(
            method: Request::METHOD_GET,
            uri: 'customers/2/orders',
        );

        $response = $this->client->getResponse();

        $decodedResponse = (array) json_decode((string) $response->getContent(), true);

        $this->assertEquals([
            [
                'last_name' => 'Galante',
                'purchase_identifier' => '2/01',
                'product_id' => 200,
                'quantity' => 25,
                'price' => 30,
                'currency' => Currency::EUR->name,
                'date' => '01/01/2024',
            ],
        ], $decodedResponse);
    }

    public function testItFailsToGetCustomerOrdersWhenCustomerIsNotFound(): void
    {
        $this->client->request(
            method: Request::METHOD_GET,
            uri: 'customers/20/orders',
        );

        $response = $this->client->getResponse();

        $decodedResponse = (array) json_decode((string) $response->getContent(), true);

        $this->assertEquals([
            'errorCode' => Response::HTTP_NOT_FOUND,
            'errorDescription' => 'Customer not found for id 20',
        ], $decodedResponse);
    }
}
