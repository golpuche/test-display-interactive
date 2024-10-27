<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Customer;
use App\Domain\Exception\CustomerNotFoundException;
use App\Domain\Port\CustomerRepositoryInterface;
use App\Domain\ValueObject\Id\CustomerId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 */
final class CustomerRepository extends ServiceEntityRepository implements CustomerRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    public function findById(CustomerId $customerId): Customer
    {
        $customer = $this->find($customerId);

        if (null === $customer) {
            throw new CustomerNotFoundException($customerId);
        }

        return $customer;
    }

    public function save(Customer $customer): void
    {
        $this->getEntityManager()->persist($customer);
        $this->getEntityManager()->flush();
    }
}
