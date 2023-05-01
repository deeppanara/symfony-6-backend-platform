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
 * /tests/Integration/Doctrine/DBAL/Types/EnumLogLoginTypeTest.php
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
use Platform\Doctrine\DBAL\Types\EnumLogLoginType;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Throwable;

/**
 * Class EnumLogLoginTypeTest
 *
 * @package App\Tests\Integration\Doctrine\DBAL\Types
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class EnumLogLoginTypeTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `getSQLDeclaration` method returns expected')]
    public function testThatGetSQLDeclarationReturnsExpected(): void
    {
        $type = $this->getType();
        $platform = $this->getPlatform();

        self::assertSame("ENUM('failure', 'success')", $type->getSQLDeclaration([], $platform));
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatConvertToDatabaseValueWorksWithProperValues')]
    #[TestDox('Test that `convertToDatabaseValue` method returns `$value`')]
    public function testThatConvertToDatabaseValueWorksWithProperValues(string $value): void
    {
        $type = $this->getType();
        $platform = $this->getPlatform();

        self::assertSame($value, $type->convertToDatabaseValue($value, $platform));
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatConvertToDatabaseValueThrowsAnException')]
    #[TestDox('Test that `convertToDatabaseValue` method throws an exception with `$value` input')]
    public function testThatConvertToDatabaseValueThrowsAnException(mixed $value): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid \'EnumLogLogin\' value');

        $type = $this->getType();
        $platform = $this->getPlatform();

        $type->convertToDatabaseValue($value, $platform);
    }

    /**
     * @return Generator<array{0: 'failure'|'success'}>
     */
    public static function dataProviderTestThatConvertToDatabaseValueWorksWithProperValues(): Generator
    {
        yield ['failure'];
        yield ['success'];
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
    private function getType(): EnumLogLoginType
    {
        Type::hasType('EnumLogLogin')
            ? Type::overrideType('EnumLogLogin', EnumLogLoginType::class)
            : Type::addType('EnumLogLogin', EnumLogLoginType::class);

        $type = Type::getType('EnumLogLogin');

        self::assertInstanceOf(EnumLogLoginType::class, $type);

        return $type;
    }
}
