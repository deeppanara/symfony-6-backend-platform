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
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Doctrine/DBAL/Types/UTCDateTimeType.php
 *
 */

namespace Platform\Doctrine\DBAL\Types;

use DateTime;
use DateTimeInterface;
use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;

/*
 *
 * @see http://doctrine-orm.readthedocs.org/en/latest/cookbook/working-with-datetime.html
 */

/**
 *
 */
class UTCDateTimeType extends DateTimeType
{
    private static ?DateTimeZone $utc = null;

    /**
     * {@inheritdoc}
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof DateTime) {
            $value->setTimezone($this->getUtcDateTimeZone());
        }

        return (string)parent::convertToDatabaseValue($value, $platform);
    }

    /**
     * @param T                $value
     * @param AbstractPlatform $platform
     *
     * @return DateTimeInterface|null (T is null ? null : DateTimeInterface)
     *
     * @throws ConversionException
     * @template T
     *
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): DateTimeInterface|null
    {
        if ($value instanceof DateTime) {
            $value->setTimezone($this->getUtcDateTimeZone());
        } elseif ($value !== null) {
            $converted = DateTime::createFromFormat(
                $platform->getDateTimeFormatString(),
                (string)$value,
                $this->getUtcDateTimeZone()
            );

            $value = $this->checkConvertedValue((string)$value, $platform, $converted !== false ? $converted : null);
        }

        return parent::convertToPHPValue($value, $platform);
    }

    /**
     * Method to initialize DateTimeZone as in UTC.
     */
    private function getUtcDateTimeZone(): DateTimeZone
    {
        return self::$utc ??= new DateTimeZone('UTC');
    }

    /**
     * Method to check if conversion was successfully or not.
     *
     * @throws ConversionException
     */
    private function checkConvertedValue(string $value, AbstractPlatform $platform, ?DateTime $converted): DateTime
    {
        if ($converted instanceof DateTime) {
            return $converted;
        }

        throw ConversionException::conversionFailedFormat(
            $value,
            $this->getName(),
            $platform->getDateTimeFormatString()
        );
    }
}
