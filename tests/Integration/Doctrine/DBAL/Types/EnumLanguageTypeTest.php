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
 * /tests/Integration/Doctrine/DBAL/Types/EnumLanguageTypeTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */

namespace App\Tests\Integration\Doctrine\DBAL\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Generator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Doctrine\DBAL\Types\EnumLanguageType;
use Platform\Enum\Language;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Throwable;

/**
 * Class EnumLanguageTypeTest
 *
 * @package App\Tests\Integration\Doctrine\DBAL\Types
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */
class EnumLanguageTypeTest extends KernelTestCase
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
    #[TestDox('Test that `convertToDatabaseValue` method returns `$expected` when using `$language`')]
    public function testThatConvertToDatabaseValueWorksWithProperValues(string $expected, Language $language): void
    {
        $type = $this->getType();
        $platform = $this->getPlatform();

        self::assertSame($expected, $type->convertToDatabaseValue($language, $platform));
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatConvertToDatabaseValueThrowsAnException')]
    #[TestDox('Test that `convertToDatabaseValue` method throws an exception with `$value` input')]
    public function testThatConvertToDatabaseValueThrowsAnException(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid \'EnumLanguage\' value');

        $type = $this->getType();
        $platform = $this->getPlatform();

        $type->convertToDatabaseValue($value, $platform);
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatConvertToPHPValueWorksWithValidInput')]
    #[TestDox('Test that `convertToPHPValue` method returns `$expected` when using `$input`')]
    public function testThatConvertToPHPValueWorksWithValidInput(Language $expected, string $input): void
    {
        $type = $this->getType();
        $platform = $this->getPlatform();

        self::assertSame($expected, $type->convertToPHPValue($input, $platform));
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatConvertToPHPValueThrowsAnException')]
    #[TestDox('Test that `convertToPHPValue` method throws an exception with `$value` input')]
    public function testThatConvertToPHPValueThrowsAnException(mixed $value): void
    {
        $this->expectException(ConversionException::class);
        $this->expectExceptionMessage('Could not convert database value');

        $type = $this->getType();
        $platform = $this->getPlatform();

        $type->convertToPHPValue($value, $platform);
    }

    /**
     * @return Generator<array{0: 'en'|'fi', 1: Language}>
     */
    public static function dataProviderTestThatConvertToDatabaseValueWorksWithProperValues(): Generator
    {
        yield ['en', Language::EN];
        yield ['fi', Language::FI];
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

    /**
     * @return Generator<array{0: Language, 1: 'en'|'fi'}>
     */
    public static function dataProviderTestThatConvertToPHPValueWorksWithValidInput(): Generator
    {
        yield [Language::EN, 'en'];
        yield [Language::FI, 'fi'];
    }

    /**
     * @return Generator<array{0: mixed}>
     */
    public static function dataProviderTestThatConvertToPHPValueThrowsAnException(): Generator
    {
        yield [null];
        yield [false];
        yield [true];
        yield [''];
        yield [' '];
        yield [1];
        yield ['foobar'];
    }

    private function getPlatform(): AbstractPlatform
    {
        return new MySQLPlatform();
    }

    /**
     * @throws Throwable
     */
    private function getType(): EnumLanguageType
    {
        Type::hasType('EnumLanguage')
            ? Type::overrideType('EnumLanguage', EnumLanguageType::class)
            : Type::addType('EnumLanguage', EnumLanguageType::class);

        $type = Type::getType('EnumLanguage');

        self::assertInstanceOf(EnumLanguageType::class, $type);

        return $type;
    }
}
