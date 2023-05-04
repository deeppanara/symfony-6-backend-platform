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
 * /tests/Integration/Doctrine/DBAL/Types/EnumLocaleTypeTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\Type;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Doctrine\DBAL\Types\EnumLocaleType;
use Platform\Enum\Locale;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Throwable;

/**
 * Class EnumLocaleTypeTest
 *
 * @package App\Tests\Integration\Doctrine\DBAL\Types
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class EnumLocaleTypeTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `getSQLDeclaration` method returns expected')]
    public function testThatGetSQLDeclarationReturnsExpected(): void
    {
        $type = $this->getType();
        $platform = $this->getPlatform();

        self::assertSame("ENUM('en', 'fi')", $type->getSQLDeclaration([], $platform));
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatConvertToDatabaseValueWorksWithProperValues')]
    #[TestDox('Test that `convertToDatabaseValue` method returns `$expected` when using `$locale`')]
    public function testThatConvertToDatabaseValueWorksWithProperValues(string $expected, Locale $locale): void
    {
        $type = $this->getType();
        $platform = $this->getPlatform();

        self::assertSame($expected, $type->convertToDatabaseValue($locale, $platform));
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatConvertToDatabaseValueThrowsAnException')]
    #[TestDox('Test that `convertToDatabaseValue` method throws an exception with `$value` input')]
    public function testThatConvertToDatabaseValueThrowsAnException(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid \'EnumLocale\' value');

        $type = $this->getType();
        $platform = $this->getPlatform();

        $type->convertToDatabaseValue($value, $platform);
    }

    /**
     * @return Generator<array{0: 'en'|'fi', 1: Locale}>
     */
    public static function dataProviderTestThatConvertToDatabaseValueWorksWithProperValues(): Generator
    {
        yield ['en', Locale::EN];
        yield ['fi', Locale::FI];
    }

    /**
     * @return Generator<array{0: mixed}>
     */
    public static function dataProviderTestThatConvertToDatabaseValueThrowsAnException(): Generator
    {
        yield [null];
        yield [false];
        yield [true];
        yield [''];
        yield [' '];
        yield ['foobar'];
        yield [[]];
        yield [new stdClass()];
    }

    private function getPlatform(): AbstractPlatform
    {
        return new MySQLPlatform();
    }

    /**
     * @throws Throwable
     */
    private function getType(): EnumLocaleType
    {
        Type::hasType('EnumLocale')
            ? Type::overrideType('EnumLocale', EnumLocaleType::class)
            : Type::addType('EnumLocale', EnumLocaleType::class);

        $type = Type::getType('EnumLocale');

        self::assertInstanceOf(EnumLocaleType::class, $type);

        return $type;
    }
}
