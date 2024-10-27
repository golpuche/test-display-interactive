<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastructure\Command;

use App\Domain\Entity\Customer;
use App\Domain\Entity\Order;
use App\Domain\ValueObject\Enum\Currency;
use App\Domain\ValueObject\Enum\CustomerTitle;
use App\Domain\ValueObject\Id\CustomerId;
use App\Domain\ValueObject\Id\OrderId;
use App\Infrastructure\Doctrine\Repository\CustomerRepository;
use App\Infrastructure\Doctrine\Repository\OrderRepository;
use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class ImportOrdersCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $entityManager = self::getContainer()->get(EntityManagerInterface::class);
        /** @var CustomerRepository $customerRepository */
        $customerRepository = self::getContainer()->get(CustomerRepository::class);
        /** @var OrderRepository $orderRepository */
        $orderRepository = self::getContainer()->get(OrderRepository::class);

        $ormExecutor = new ORMExecutor($entityManager, new ORMPurger());
        $ormExecutor->purge();

        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = $application->find('ugo:orders:import');
        $commandTester = new CommandTester($command);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('Orders import has been successfully completed', $output);

        self::assertEquals([
            $customerOne = Customer::create(
                id: new CustomerId(1),
                title: CustomerTitle::M,
                lastname: 'Norris',
                firstname: 'Chuck',
                postalCode: '83600',
                city: 'Fréjus',
                email: 'chuck@norris.com',
            ),
            $customerTwo = Customer::create(
                id: new CustomerId(2),
                title: CustomerTitle::MME,
                lastname: 'Galante',
                firstname: 'Marie',
                postalCode: null,
                city: null,
                email: 'marie-galante@france.fr',
            ),
            $customerThree = Customer::create(
                id: new CustomerId(3),
                title: CustomerTitle::M,
                lastname: 'Barbier',
                firstname: 'Christophe',
                postalCode: '75009',
                city: 'Paris',
                email: 'christophe@fake.email',
            ),
            Customer::create(
                id: new CustomerId(4),
                title: CustomerTitle::MME,
                lastname: null,
                firstname: null,
                postalCode: null,
                city: null,
                email: null,
            ),
        ], $customerRepository->findAll());

        self::assertEquals([
            Order::create(
                id: new OrderId('2/01'),
                customer: $customerTwo,
                productId: 1221,
                quantity: 1,
                price: 10,
                currency: Currency::EUR,
                date: new \DateTimeImmutable('2017-12-31'),
            ),
            Order::create(
                id: new OrderId('1/01'),
                customer: $customerOne,
                productId: 4324,
                quantity: 1,
                price: 10,
                currency: Currency::EUR,
                date: new \DateTimeImmutable('2030-12-31'),
            ),
            Order::create(
                id: new OrderId('3/01'),
                customer: $customerThree,
                productId: 75672,
                quantity: 1,
                price: 10,
                currency: Currency::USD,
                date: new \DateTimeImmutable('2050-12-31'),
            ),
            Order::create(
                id: new OrderId('3/02'),
                customer: $customerThree,
                productId: 2123,
                quantity: 1,
                price: 10,
                currency: Currency::EUR,
                date: new \DateTimeImmutable('2017-08-01'),
            ),
            Order::create(
                id: new OrderId('2/02'),
                customer: $customerTwo,
                productId: 3213,
                quantity: 1,
                price: 10,
                currency: Currency::EUR,
                date: new \DateTimeImmutable('2030-12-31'),
            ),
        ], $orderRepository->findAll());
    }
}
