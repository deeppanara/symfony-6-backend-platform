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
 * /tests/Integration/ValueResolver/RestDtoValueResolverTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\ValueResolver;

use AutoMapperPlus\AutoMapperInterface;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\MockObject\MockBuilder;
use Platform\DTO\RestDtoInterface;
use Platform\Rest\Controller;
use Platform\Rest\ControllerCollection;
use Platform\ValueResolver\RestDtoValueResolver;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Throwable;

/**
 * Class RestDtoValueResolverTest
 *
 * @package App\Tests\Integration\ValueResolver
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class RestDtoValueResolverTest extends KernelTestCase
{
    /**
     * @psalm-param ControllerCollection<Controller> $controllerCollection
     */
    #[DataProvider('dataProviderTestThatSupportMethodWorksAsExpected')]
    #[TestDox('Test that `supports` method returns expected result `$expected`')]
    public function testThatSupportMethodWorksAsExpected(
        bool $expected,
        ControllerCollection $controllerCollection,
        Request $request,
        ArgumentMetadata $argumentMetadata
    ): void {
        $autoMapper = $this->getMockBuilder(AutoMapperInterface::class)->getMock();

        $resolver = new RestDtoValueResolver($controllerCollection, $autoMapper);

        self::assertSame($expected, $resolver->supports($request, $argumentMetadata));
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `resolve` method works as expected')]
    public function testThatResolveMethodWorksAsExpected(): void
    {
        $controllerCollection = $this->getMockBuilder(ControllerCollection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $autoMapper = $this->getMockBuilder(AutoMapperInterface::class)->getMock();
        $controller = $this->getMockBuilder(Controller::class)->disableOriginalConstructor()->getMock();
        $restDto = $this->getMockBuilder(RestDtoInterface::class)->getMock();

        $resolver = new RestDtoValueResolver($controllerCollection, $autoMapper);
        $metadata = new ArgumentMetadata('foo', RestDtoInterface::class, false, false, null);
        $request = new Request(attributes: [
            '_controller' => 'foo::createAction',
        ]);

        $resolver->supports($request, $metadata);

        $dto = $this->getMockBuilder(RestDtoInterface::class)->getMock();
        $controllerCollection
            ->expects(self::exactly(2))
            ->method('has')
            ->with('foo')
            ->willReturn(true);

        $controllerCollection
            ->expects(self::once())
            ->method('get')
            ->with('foo')
            ->willReturn($controller);

        $controller
            ->expects(self::once())
            ->method('getDtoClass')
            ->with('createMethod')
            ->willReturn(RestDtoInterface::class);

        $autoMapper
            ->expects(self::once())
            ->method('map')
            ->with($request, RestDtoInterface::class)
            ->willReturn($restDto);

        $resolver->supports($request, $metadata);

        static::assertSame($restDto, $resolver->resolve($request, $metadata)->current());
    }

    public static function dataProviderTestThatSupportMethodWorksAsExpected(): Generator
    {
        /** @psalm-suppress InternalMethod */
        $controllerCollection = (new MockBuilder(new self(self::class), ControllerCollection::class))
            ->disableOriginalConstructor()
            ->getMock();

        $argumentMetaData = new ArgumentMetadata('foo', null, false, false, null);

        yield [
            false,
            $controllerCollection,
            Request::create('/'),
            $argumentMetaData,
        ];

        $request = new Request(attributes: [
            '_controller' => 'foo::bar',
        ]);

        yield [
            false,
            $controllerCollection,
            $request,
            $argumentMetaData,
        ];

        /** @psalm-suppress InternalMethod */
        $controllerCollection = (new MockBuilder(new self(self::class), ControllerCollection::class))
            ->disableOriginalConstructor()
            ->getMock();

        $controllerCollection
            ->expects(self::once())
            ->method('has')
            ->with('foo')
            ->willReturn(false);

        $request = new Request(attributes: [
            '_controller' => 'foo::createAction',
        ]);

        $argumentMetaData = new ArgumentMetadata('foo', RestDtoInterface::class, false, false, null);

        yield [
            false,
            $controllerCollection,
            $request,
            $argumentMetaData,
        ];

        /** @psalm-suppress InternalMethod */
        $controllerCollection = (new MockBuilder(new self(self::class), ControllerCollection::class))
            ->disableOriginalConstructor()
            ->getMock();

        $controllerCollection
            ->expects(self::never())
            ->method('has')
            ->with('foo');

        $request = new Request(attributes: [
            '_controller' => 'foo::createAction',
        ]);

        $argumentMetaData = new ArgumentMetadata('foo', null, false, false, null);

        yield [
            false,
            $controllerCollection,
            $request,
            $argumentMetaData,
        ];

        /** @psalm-suppress InternalMethod */
        $controllerCollection = (new MockBuilder(new self(self::class), ControllerCollection::class))
            ->disableOriginalConstructor()
            ->getMock();

        $controllerCollection
            ->expects(self::once())
            ->method('has')
            ->with('foo')
            ->willReturn(true);

        $request = new Request(attributes: [
            '_controller' => 'foo::createAction',
        ]);

        $argumentMetaData = new ArgumentMetadata('foo', RestDtoInterface::class, false, false, null);

        yield [
            true,
            $controllerCollection,
            $request,
            $argumentMetaData,
        ];
    }
}
