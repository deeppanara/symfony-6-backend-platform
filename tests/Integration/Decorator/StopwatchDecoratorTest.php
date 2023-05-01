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
 * @date     01/05/23, 12:25 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /tests/Integration/Decorator/StopwatchDecoratorTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Decorator;

use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Decorator\StopwatchDecorator;
use Platform\Entity\ApiKey;
use Platform\Repository\ApiKeyRepository;
use Platform\Resource\ApiKeyResource;
use Platform\Validator\Constraints\EntityReferenceExists;
use ProxyManager\Factory\AccessInterceptorValueHolderFactory;
use ProxyManager\Proxy\AccessInterceptorValueHolderInterface;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Stopwatch\Stopwatch;
use Throwable;
use function method_exists;

/**
 * Class StopwatchDecoratorTest
 *
 * @package App\Tests\Decorator\Service
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class StopwatchDecoratorTest extends KernelTestCase
{
    /**
     * @param class-string $expected
     */
    #[DataProvider('dataProviderTestThatDecorateMethodReturnsExpected')]
    #[TestDox('Test that `decorate` method returns `$expected` when using `$service` instance as an input')]
    public function testThatDecorateMethodReturnsExpected(string $expected, object $service): void
    {
        $decorator = new StopwatchDecorator(new AccessInterceptorValueHolderFactory(), new Stopwatch());

        self::assertInstanceOf($expected, $decorator->decorate($service));
    }

    #[TestDox('Test that decorator calls expected methods from `StopWatch` service')]
    public function testThatDecoratorCallsStopWatchStartAndStopMethods(): void
    {
        $stopWatch = $this->getMockBuilder(Stopwatch::class)->disableOriginalConstructor()->getMock();

        $stopWatch
            ->expects(self::once())
            ->method('start')
            ->with('EntityReferenceExists->getTargets', EntityReferenceExists::class);

        $stopWatch
            ->expects(self::once())
            ->method('stop')
            ->with('EntityReferenceExists->getTargets');

        $decorator = new StopwatchDecorator(new AccessInterceptorValueHolderFactory(), $stopWatch);

        $decoratedService = $decorator->decorate(new EntityReferenceExists());

        self::assertTrue(method_exists($decoratedService, 'getTargets'));
        self::assertSame('property', $decoratedService->getTargets());
    }

    #[TestDox('Test that `decorate` method returns exact same service if factory throws an exception')]
    public function testThatDecoratorReturnsTheSameInstanceIfFactoryFails(): void
    {
        $service = new EntityReferenceExists();

        $factory = $this->getMockBuilder(AccessInterceptorValueHolderFactory::class)
            ->disableOriginalConstructor()
            ->getMock();

        $stopWatch = $this->getMockBuilder(Stopwatch::class)->disableOriginalConstructor()->getMock();

        $factory
            ->expects(self::once())
            ->method('createProxy')
            ->willThrowException(new Exception('foo'));

        $decorator = new StopwatchDecorator($factory, $stopWatch);

        self::assertSame($service, $decorator->decorate($service));
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `decorate` method decorates possible inner objects / services')]
    public function testThatDecoratorAlsoDecoratesInnerObjects(): void
    {
        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $stopWatch = $this->getMockBuilder(Stopwatch::class)->disableOriginalConstructor()->getMock();

        $managerRegistry
            ->expects(self::once())
            ->method('getManagerForClass')
            ->willReturn($entityManager);

        $stopWatch
            ->expects(self::exactly(2))
            ->method('start');

        $stopWatch
            ->expects(self::exactly(2))
            ->method('stop');

        $decorator = new StopwatchDecorator(new AccessInterceptorValueHolderFactory(), $stopWatch);
        $repository = new ApiKeyRepository($managerRegistry);
        $resource = new ApiKeyResource($repository);

        /** @var ApiKeyResource $decoratedService */
        $decoratedService = $decorator->decorate($resource);
        $decoratedService->getRepository()->getEntityManager();
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `decorate` method does not decorate entity objects')]
    public function testThatDecoratorDoesNotTryToDecorateEntityObjects(): void
    {
        $apiKey = new ApiKey();

        $managerRegistry = $this->getMockBuilder(ManagerRegistry::class)->disableOriginalConstructor()->getMock();
        $entityManager = $this->getMockBuilder(EntityManager::class)->disableOriginalConstructor()->getMock();
        $stopWatch = $this->getMockBuilder(Stopwatch::class)->disableOriginalConstructor()->getMock();

        $managerRegistry
            ->expects(self::once())
            ->method('getManagerForClass')
            ->willReturn($entityManager);

        $entityManager
            ->expects(self::once())
            ->method('find')
            ->willReturn($apiKey);

        $stopWatch
            ->expects(self::once())
            ->method('start');

        $stopWatch
            ->expects(self::once())
            ->method('stop');

        $decorator = new StopwatchDecorator(new AccessInterceptorValueHolderFactory(), $stopWatch);
        $repository = new ApiKeyRepository($managerRegistry);

        /** @var ApiKeyRepository $decoratedService */
        $decoratedService = $decorator->decorate($repository);

        self::assertSame($apiKey, $decoratedService->find($apiKey->getId()));
    }

    /**
     * @return Generator<array{0: string, 1: object}>
     */
    public static function dataProviderTestThatDecorateMethodReturnsExpected(): Generator
    {
        yield [AccessInterceptorValueHolderInterface::class, new EntityReferenceExists()];
        yield [stdClass::class, new stdClass()];
    }
}
