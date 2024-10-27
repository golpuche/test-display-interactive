<?php

declare(strict_types=1);

namespace Tests\Integration\Fixture;

use App\Domain\Entity\Customer;
use App\Domain\Entity\Order;
use App\Domain\ValueObject\Enum\Currency;
use App\Domain\ValueObject\Id\OrderId;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class OrderFixture extends AbstractFixture implements FixtureInterface, DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist(Order::create(
            id: new OrderId('1/01'),
            customer: $this->getReference('customer_1', Customer::class),
            productId: 200,
            quantity: 20,
            price: 30,
            currency: Currency::EUR,
            date: new \DateTimeImmutable('2024-01-01'),
        ));

        $manager->persist(Order::create(
            id: new OrderId('1/02'),
            customer: $this->getReference('customer_1', Customer::class),
            productId: 200,
            quantity: 25,
            price: 30,
            currency: Currency::EUR,
            date: new \DateTimeImmutable('2024-01-01'),
        ));

        $manager->persist(Order::create(
            id: new OrderId('2/01'),
            customer: $this->getReference('customer_2', Customer::class),
            productId: 200,
            quantity: 25,
            price: 30,
            currency: Currency::EUR,
            date: new \DateTimeImmutable('2024-01-01'),
        ));

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CustomerFixture::class,
        ];
    }
}
