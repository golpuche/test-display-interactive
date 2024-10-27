<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type;

use App\Domain\ValueObject\Id\CustomerId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

final class CustomerIdType extends Type
{
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getIntegerTypeDeclarationSQL($column);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): int
    {
        if (!$value instanceof CustomerId) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['int', CustomerId::class]);
        }

        return $value->getId();
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): CustomerId
    {
        if (!\is_int($value)) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['int', CustomerId::class]);
        }

        return new CustomerId($value);
    }

    public function getName(): string
    {
        return 'customer_id';
    }
}
