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
 * @date     01/05/23, 12:17 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /tests/Integration/TestCase/RestRequestMapperTestCase.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\TestCase;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockObject;
use Platform\AutoMapper\RestRequestMapper;
use Platform\Rest\Interfaces\RestResourceInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Throwable;
use function class_exists;

/**
 * Class RestRequestMapperTestCase
 *
 * @package App\Tests\Integration\TestCase
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
abstract class RestRequestMapperTestCase extends KernelTestCase
{
    protected ?RestRequestMapper $mapperObject = null;

    /**
     * @var array<int, class-string>
     */
    protected static array $restDtoClasses = [];

    /**
     * @param class-string $expectedDto
     *
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatMapMethodWorksAsExpected')]
    #[TestDox('Test that `map` method returns `$expectedDto` DTO object')]
    public function testThatMapMethodWorksAsExpected(string $expectedDto): void
    {
        self::assertInstanceOf(
            $expectedDto,
            $this->getRequestMapper()->map(new Request(), $expectedDto),
        );
    }

    /**
     * @param class-string $expectedDto
     *
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatMapToObjectReturnsExpectedDtoObject')]
    #[TestDox('Test that `mapToObject` method returns `$expectedDto` DTO object')]
    public function testThatMapToObjectReturnsExpectedDtoObject(string $expectedDto): void
    {
        self::assertTrue(class_exists($expectedDto));

        self::assertInstanceOf(
            $expectedDto,
            $this->getRequestMapper()->mapToObject(new Request(), new $expectedDto()),
        );
    }

    /**
     * @return Generator<array{0: class-string}>
     */
    public static function dataProviderTestThatMapMethodWorksAsExpected(): Generator
    {
        foreach (static::$restDtoClasses as $dtoClass) {
            yield [$dtoClass];
        }
    }

    /**
     * @return Generator<array{0: class-string}>
     */
    public static function dataProviderTestThatMapToObjectReturnsExpectedDtoObject(): Generator
    {
        return self::dataProviderTestThatMapMethodWorksAsExpected();
    }

    abstract protected function getRequestMapper(): RestRequestMapper;

    /**
     * @phpstan-return  MockObject&RestResourceInterface
     */
    abstract protected function getResource(): MockObject;
}
