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
 * /tests/Integration/Rest/ControllerCollectionTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Rest;

use ArrayObject;
use Generator;
use InvalidArgumentException;
use IteratorAggregate;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Controller\v1\ApiKey\ApiKeyController;
use Platform\Controller\v1\Auth\GetTokenController;
use Platform\Controller\v1\Role\FindOneRoleController;
use Platform\Controller\v1\Role\RoleController;
use Platform\Controller\v1\User\DeleteUserController;
use Platform\Controller\v1\User\UserController;
use Platform\Controller\v1\UserGroup\UserGroupController;
use Platform\Rest\Controller;
use Platform\Rest\ControllerCollection;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Throwable;

/**
 * Class ControllerCollectionTest
 *
 * @package App\Tests\Integration\Rest
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class ControllerCollectionTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `get` method throws an exception when specified `REST` controller is not found')]
    public function testThatGetMethodThrowsAnException(): void
    {
        $stubLogger = $this->createMock(LoggerInterface::class);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('REST controller \'FooBar\' does not exist');

        /** @psalm-suppress MissingTemplateParam */
        $iteratorAggregate = new class([]) implements IteratorAggregate {
            /**
             * @phpstan-var ArrayObject<int, mixed>
             */
            private readonly ArrayObject $iterator;

            /**
             * Constructor of the class.
             *
             * @param array<mixed> $input
             */
            public function __construct(array $input)
            {
                $this->iterator = new ArrayObject($input);
            }

            /**
             * @phpstan-return ArrayObject<int, mixed>
             */
            public function getIterator(): ArrayObject
            {
                return $this->iterator;
            }
        };

        (new ControllerCollection($iteratorAggregate, $stubLogger))->get('FooBar');
    }

    #[TestDox('Test that `getAll` method returns expected count of `REST` controllers')]
    public function testThatGetAllReturnsCorrectCountOfRestControllers(): void
    {
        $collection = $this->getCollection();

        self::assertCount(13, $collection->getAll());
    }

    /**
     * @param class-string<Controller> $controllerName
     */
    #[DataProvider('dataProviderTestThatGetReturnsExpectedController')]
    #[TestDox('Test that `get` method with `$controllerName` input returns instance of that controller')]
    public function testThatGetReturnsExpectedController(string $controllerName): void
    {
        $collection = $this->getCollection();

        self::assertInstanceOf($controllerName, $collection->get($controllerName));
    }

    /**
     * @param class-string<Controller>|string|null $controller
     */
    #[DataProvider('dataProviderTestThatHasReturnsExpected')]
    #[TestDox('Test that `has` method returns `$expected` with `$controller` input')]
    public function testThatHasReturnsExpected(bool $expected, ?string $controller): void
    {
        $collection = $this->getCollection();

        self::assertSame($expected, $collection->has($controller));
    }

    /**
     * @return Generator<array{0: class-string<Controller>}>
     */
    public static function dataProviderTestThatGetReturnsExpectedController(): Generator
    {
        yield [ApiKeyController::class];
        yield [RoleController::class];
        yield [FindOneRoleController::class];
        yield [UserController::class];
        yield [UserGroupController::class];
        yield [DeleteUserController::class];
    }

    /**
     * @return Generator<array{0: boolean, 1: class-string<Controller>|string|null}>
     */
    public static function dataProviderTestThatHasReturnsExpected(): Generator
    {
        yield [true, ApiKeyController::class];
        yield [true, RoleController::class];
        yield [true, FindOneRoleController::class];
        yield [true, UserController::class];
        yield [true, UserGroupController::class];
        yield [true, DeleteUserController::class];
        yield [false, null];
        yield [false, 'foobar'];
        yield [false, GetTokenController::class];
    }

    private function getCollection(): ControllerCollection
    {
        self::bootKernel();

        return self::getContainer()->get(ControllerCollection::class);
    }
}