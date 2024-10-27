<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\ValueObject\Id\OrderId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

final class OrderIdType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getStringTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!$value instanceof OrderId) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['string', OrderId::class]);
        }

        return $value->getId();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): OrderId
    {
        if (!\is_string($value)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['string', OrderId::class]);
        }

        return new OrderId($value);
    }

    public function getName(): string
    {
        return 'order_id';
    }
}
