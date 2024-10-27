<?php

declare(strict_types=1);

namespace Tests\Integration\Fixture;

use App\Domain\Entity\Customer;
use App\Domain\ValueObject\Enum\CustomerTitle;
use App\Domain\ValueObject\Id\CustomerId;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;

final class CustomerFixture extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist($customerOne = Customer::create(
            id: new CustomerId(1),
            title: CustomerTitle::M,
            lastname: 'Norris',
            firstname: 'Chuck',
            postalCode: '83600',
            city: 'Fréjus',
            email: 'chuck@norris.com',
        ));

        $this->addReference('customer_1', $customerOne);

        $manager->persist($customerTwo = Customer::create(
            id: new CustomerId(2),
            title: CustomerTitle::MME,
            lastname: 'Galante',
            firstname: 'Marie',
            postalCode: null,
            city: null,
            email: 'marie-galante@france.fr',
        ));

        $this->addReference('customer_2', $customerTwo);

        $manager->flush();
    }
}
