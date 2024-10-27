<?php

declare(strict_types=1);

namespace Tests\Integration\UI\Controller;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Tests\Integration\Fixture\CustomerFixture;

class GetCustomersControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();

        $entityManager = self::getContainer()->get(EntityManagerInterface::class);

        $ormExecutor = new ORMExecutor($entityManager, new ORMPurger());
        $ormExecutor->purge();
        $ormExecutor->execute([new CustomerFixture()]);
    }

    public function testItSuccessToGetCustomers(): void
    {
        $this->client->request(
            method: Request::METHOD_GET,
            uri: 'customers',
        );

        $response = $this->client->getResponse();

        $decodedResponse = (array) json_decode((string) $response->getContent(), true);

        $this->assertEquals([
            [
                'id' => 1,
                'title' => 'm',
                'lastname' => 'Norris',
                'firstname' => 'Chuck',
                'postal_code' => '83600',
                'city' => 'Fréjus',
                'email' => 'chuck@norris.com',
            ],
            [
                'id' => 2,
                'title' => 'mme',
                'lastname' => 'Galante',
                'firstname' => 'Marie',
                'postal_code' => null,
                'city' => null,
                'email' => 'marie-galante@france.fr',
            ],
        ], $decodedResponse);
    }
}
