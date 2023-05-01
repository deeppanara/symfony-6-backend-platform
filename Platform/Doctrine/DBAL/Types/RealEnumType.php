<?php
/*
 * *************************************************************************
 * Copyright (C) 2023, Inc - All Rights Reserved
 * This file is part of the Dom bundle.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author   Deep Panara <panaradeep@gmail.com>
 * @date     01/05/23, 12:13 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Doctrine/DBAL/Types/RealEnumType.php
 *
 */

namespace Platform\Doctrine\DBAL\Types;

use BackedEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use Platform\Enum\Interfaces\DatabaseEnumInterface;
use function array_map;
use function gettype;
use function implode;
use function in_array;
use function is_string;


/**
 *
 */
abstract class RealEnumType extends Type
{
    protected static string $name;

    /**
     * @psalm-var class-string<DatabaseEnumInterface&BackedEnum>
     */
    protected static string $enum;

    /**
     * @return array<int, string>
     */
    public static function getValues(): array
    {
        return static::$enum::getValues();
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $enumDefinition = implode(
            ', ',
            array_map(static fn (string $value): string => "'" . $value . "'", static::getValues()),
        );

        return 'ENUM(' . $enumDefinition . ')';
    }

    /**
     * @inheritDoc
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (!in_array($value, static::$enum::cases(), true)) {
            $message = sprintf(
                "Invalid '%s' value '%s'",
                static::$name,
                is_string($value) ? $value : gettype($value),
            );

            throw new InvalidArgumentException($message);
        }

        return (string)parent::convertToDatabaseValue($value->value, $platform);
    }

    /**
     * @inheritDoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): DatabaseEnumInterface
    {
        $value = (string)parent::convertToPHPValue($value, $platform);
        $enum = static::$enum::tryFrom($value);

        if ($enum !== null) {
            return $enum;
        }

        throw ConversionException::conversionFailedFormat(
            gettype($value),
            static::$name,
            'One of: "' . implode('", "', static::getValues()) . '"',
        );
    }

    /**
     * Parent method is deprecated, so remove this after it has been removed.
     *
     * @codeCoverageIgnore
     */
    public function getName(): string
    {
        return '';
    }
}
