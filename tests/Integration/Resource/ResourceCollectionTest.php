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
 * /tests/Integration/Resource/ResourceCollectionTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Resource;

use ArrayObject;
use Generator;
use InvalidArgumentException;
use IteratorAggregate;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Entity\ApiKey;
use Platform\Entity\DateDimension;
use Platform\Entity\Healthz;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Entity\LogLogin;
use Platform\Entity\LogLoginFailure;
use Platform\Entity\LogRequest;
use Platform\Entity\Role;
use Platform\Entity\User;
use Platform\Entity\UserGroup;
use Platform\Resource\ApiKeyResource;
use Platform\Resource\DateDimensionResource;
use Platform\Resource\HealthzResource;
use Platform\Resource\LogLoginFailureResource;
use Platform\Resource\LogLoginResource;
use Platform\Resource\LogRequestResource;
use Platform\Resource\ResourceCollection;
use Platform\Resource\RoleResource;
use Platform\Resource\UserGroupResource;
use Platform\Resource\UserResource;
use Platform\Rest\RestResource;
use Psr\Log\LoggerInterface;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Throwable;

/**
 * Class ResourceCollectionTest
 *
 * @package App\Tests\Integration\Resource
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class ResourceCollectionTest extends KernelTestCase
{
    public function testThatGetMethodThrowsAnException(): void
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Resource \'FooBar\' does not exist');

        (new ResourceCollection($this->getEmptyIteratorAggregate(), $logger))
            ->get('FooBar');
    }

    public function testThatLoggerIsCalledIfGetMethodGetIteratorThrowsAnException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Resource \'FooBar\' does not exist');

        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        $logger
            ->expects(self::once())
            ->method('error');

        (new ResourceCollection($this->getIteratorAggregateThatThrowsAnException(), $logger))
            ->get('FooBar');
    }

    public function testThatGetEntityResourceMethodThrowsAnException(): void
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Resource class does not exist for entity \'FooBar\'');

        (new ResourceCollection($this->getEmptyIteratorAggregate(), $logger))
            ->getEntityResource('FooBar');
    }

    public function testThatLoggerIsCalledIfGetEntityResourceMethodGetIteratorThrowsAnException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Resource class does not exist for entity \'FooBar\'');

        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();

        $logger
            ->expects(self::once())
            ->method('error');

        (new ResourceCollection($this->getIteratorAggregateThatThrowsAnException(), $logger))
            ->getEntityResource('FooBar');
    }

    public function testThatGetAllReturnsCorrectCountOfResources(): void
    {
        self::assertCount(10, $this->getCollection()->getAll());
    }

    public function testThatCountMethodReturnsExpectedCount(): void
    {
        self::assertSame(10, $this->getCollection()->count(), 'REST resource count from collection was not expected');
    }

    /**
     * @param class-string<RestResource> $resourceClass
     *
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatGetReturnsExpectedResource')]
    #[TestDox('Test that `get` method with `$resourceClass` input returns instance of that resource class.')]
    public function testThatGetReturnsExpectedResource(string $resourceClass): void
    {
        self::assertInstanceOf($resourceClass, $this->getCollection()->get($resourceClass));
    }

    /**
     * @param class-string<RestResource> $resourceClass
     * @param class-string<EntityInterface> $entityClass
     *
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatGetEntityResourceReturnsExpectedResource')]
    #[TestDox('Test that `getEntityResource` method with `$entityClass` input returns `$resourceClass` class.')]
    public function testThatGetEntityResourceReturnsExpectedResource(string $resourceClass, string $entityClass): void
    {
        /** @noinspection UnnecessaryAssertionInspection */
        self::assertInstanceOf($resourceClass, $this->getCollection()->getEntityResource($entityClass));
    }

    /**
     * @param class-string<RestResource>|string|null $resource
     *
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatHasReturnsExpected')]
    #[TestDox('Test that `has` method returns `$expected` with `$resource` input.')]
    public function testThatHasReturnsExpected(bool $expected, ?string $resource): void
    {
        self::assertSame($expected, $this->getCollection()->has($resource));
    }

    /**
     * @param class-string<EntityInterface>|string|null $entity
     *
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatHasEntityResourceReturnsExpected')]
    #[TestDox('Test that `hasEntityResource` method returns `$expected` with `$entity` input.')]
    public function testThatHasEntityResourceReturnsExpected(bool $expected, ?string $entity): void
    {
        self::assertSame($expected, $this->getCollection()->hasEntityResource($entity));
    }

    /**
     * @return Generator<array{0: class-string<RestResource>}>
     */
    public static function dataProviderTestThatGetReturnsExpectedResource(): Generator
    {
        yield [ApiKeyResource::class];
        yield [DateDimensionResource::class];
        yield [HealthzResource::class];
        yield [LogLoginFailureResource::class];
        yield [LogLoginResource::class];
        yield [LogRequestResource::class];
        yield [RoleResource::class];
        yield [UserGroupResource::class];
        yield [UserResource::class];
    }

    /**
     * @return Generator<array{
     *      0: class-string<RestResource>,
     *      1: class-string<EntityInterface>
     *  }>
     */
    public static function dataProviderTestThatGetEntityResourceReturnsExpectedResource(): Generator
    {
        yield [ApiKeyResource::class, ApiKey::class];
        yield [DateDimensionResource::class, DateDimension::class];
        yield [HealthzResource::class, Healthz::class];
        yield [LogLoginFailureResource::class, LogLoginFailure::class];
        yield [LogLoginResource::class, LogLogin::class];
        yield [LogRequestResource::class, LogRequest::class];
        yield [RoleResource::class, Role::class];
        yield [UserGroupResource::class, UserGroup::class];
        yield [UserResource::class, User::class];
    }

    /**
     * @return Generator<array{0: boolean, 1: class-string<RestResource>|string|null}>
     */
    public static function dataProviderTestThatHasReturnsExpected(): Generator
    {
        yield [true, ApiKeyResource::class];
        yield [true, DateDimensionResource::class];
        yield [true, HealthzResource::class];
        yield [true, LogLoginFailureResource::class];
        yield [true, LogLoginResource::class];
        yield [true, LogRequestResource::class];
        yield [true, RoleResource::class];
        yield [true, UserGroupResource::class];
        yield [true, UserResource::class];
        yield [false, null];
        yield [false, 'ResourceThatDoesNotExists'];
        yield [false, stdClass::class];
    }

    /**
     * @return Generator<array{0: boolean, 1: class-string<EntityInterface>|string|null}>
     */
    public static function dataProviderTestThatHasEntityResourceReturnsExpected(): Generator
    {
        yield [true, ApiKey::class];
        yield [true, DateDimension::class];
        yield [true, Healthz::class];
        yield [true, LogLoginFailure::class];
        yield [true, LogLogin::class];
        yield [true, LogRequest::class];
        yield [true, Role::class];
        yield [true, UserGroup::class];
        yield [true, User::class];
        yield [false, null];
        yield [false, 'ResourceThatDoesNotExists'];
        yield [false, stdClass::class];
    }

    /**
     * @throws Throwable
     */
    private function getCollection(): ResourceCollection
    {
        return self::getContainer()->get(ResourceCollection::class);
    }

    /**
     * @return IteratorAggregate<mixed>
     */
    private function getEmptyIteratorAggregate(): IteratorAggregate
    {
        /** @psalm-suppress MissingTemplateParam */
        return new class([]) implements IteratorAggregate {
            /**
             * @phpstan-var ArrayObject<int, mixed>
             */
            private readonly ArrayObject $iterator;

            /**
             * @param array<int, mixed> $input
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
    }

    /**
     * @return IteratorAggregate<mixed>
     */
    private function getIteratorAggregateThatThrowsAnException(): IteratorAggregate
    {
        /** @psalm-suppress MissingTemplateParam */
        return new class() implements IteratorAggregate {
            /**
             * @phpstan-return ArrayObject<int, mixed>
             */
            public function getIterator(): ArrayObject
            {
                throw new LogicException('Exception with getIterator');
            }
        };
    }
}