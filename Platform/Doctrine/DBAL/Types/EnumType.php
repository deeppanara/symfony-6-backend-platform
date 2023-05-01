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
 * /src/Doctrine/DBAL/Types/EnumType.php
 *
 */

namespace Platform\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use function array_map;
use function implode;
use function in_array;
use function is_string;
use function sprintf;


/**
 *
 */
abstract class EnumType extends Type
{
    protected static string $name;

    /**
     * @var array<int, string>
     */
    protected static array $values = [];

    /**
     * @return array<int, string>
     */
    public static function getValues(): array
    {
        return static::$values;
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        $iterator = static fn (string $value): string => "'" . $value . "'";

        return 'ENUM(' . implode(', ', array_map($iterator, self::getValues())) . ')';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        $value = (string)parent::convertToDatabaseValue(is_string($value) ? $value : '', $platform);

        if (!in_array($value, static::$values, true)) {
            $message = sprintf(
                "Invalid '%s' value",
                $this->getName()
            );

            throw new InvalidArgumentException($message);
        }

        return $value;
    }

    public function getName(): string
    {
        return static::$name;
    }
}
