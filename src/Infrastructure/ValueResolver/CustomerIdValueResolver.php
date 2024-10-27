<?php

declare(strict_types=1);

namespace App\Infrastructure\ValueResolver;

use App\Domain\ValueObject\Id\CustomerId;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsTargetedValueResolver;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

#[AsTargetedValueResolver(self::VALUE_RESOLVER_ALIAS)]
final class CustomerIdValueResolver implements ValueResolverInterface
{
    public const VALUE_RESOLVER_ALIAS = 'customer_id';
    private const ALIAS_IN_REQUEST = 'customerId';

    public function resolve(Request $request, ArgumentMetadata $argument): array
    {
        if (!$this->supports($argument)) {
            return [];
        }

        return [new CustomerId($this->getCustomerIdFromRequest($request, $argument))];
    }

    private function supports(ArgumentMetadata $argument): bool
    {
        return CustomerId::class === $argument->getType();
    }

    private function getCustomerIdFromRequest(Request $request, ArgumentMetadata $argument): int
    {
        $customerId = $request->attributes->get(
            $argument->getName(),
            $request->attributes->get(self::ALIAS_IN_REQUEST)
        );

        if (!is_numeric($customerId)) {
            throw new \LogicException('Customer id is not defined correctly, it should be an int');
        }

        return (int) $customerId;
    }
}
