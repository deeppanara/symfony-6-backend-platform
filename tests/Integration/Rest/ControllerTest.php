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
 * /tests/Integration/Rest/ControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Rest;

use App\Tests\Integration\Rest\src\AbstractController as Controller;
use App\Tests\Utils\PhpUnitUtil;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Controller\v1\ApiKey\ApiKeyController;
use Platform\DTO\ApiKey\ApiKey;
use Platform\DTO\RestDtoInterface;
use Platform\Resource\ApiKeyResource;
use Platform\Rest\ResponseHandler;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Serializer;
use Throwable;
use UnexpectedValueException;

/**
 * Class ControllerTest
 *
 * @package App\Tests\Integration\Rest
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class ControllerTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `getResource` method throws an exception if `Resource` service is not set')]
    public function testThatGetResourceThrowsAnExceptionIfNotSet(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('Resource service not set');

        $this->getMockForAbstractClass(Controller::class, [], '', false)
            ->getResource();
    }

    /**
     * @throws Throwable
     */
    #[TestDox("Test that `getResource` method doesn't throw an exception if `Resource` service is set")]
    public function testThatGetResourceDoesNotThrowsAnExceptionIfSet(): void
    {
        $this->expectNotToPerformAssertions();

        $resourceMock = $this->getMockBuilder(ApiKeyResource::class)->disableOriginalConstructor()->getMock();

        $controller = new ApiKeyController($resourceMock);
        $controller->setResponseHandler(new ResponseHandler(new Serializer()));
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `getResponseHandler` method throws an exception if `ResponseHandler` service is not set')]
    public function testThatGetResponseHandlerThrowsAnExceptionIfNotSet(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage('ResponseHandler service not set');

        $this->getMockForAbstractClass(Controller::class, [], '', false)
            ->getResponseHandler();
    }

    /**
     * @throws Throwable
     */
    #[TestDox("Test that `getResponseHandler` method doesn't throw an exception if `ResponseHandler` service is set")]
    public function testThatGetResponseHandlerDoesNotThrowsAnExceptionIfSet(): void
    {
        $resourceMock = $this->getMockBuilder(ApiKeyResource::class)->disableOriginalConstructor()->getMock();

        $controller = new ApiKeyController($resourceMock);
        $controller->setResponseHandler(new ResponseHandler(new Serializer()));

        self::assertInstanceOf(ResponseHandler::class, $controller->getResponseHandler());
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `getDtoClass` method calls expected `Resource` service method')]
    public function testThatGetDtoClassCallsExpectedServiceMethods(): void
    {
        $dtoClassMock = $this->getMockBuilder(RestDtoInterface::class)->getMock();
        $resourceMock = $this->getMockBuilder(ApiKeyResource::class)->disableOriginalConstructor()->getMock();

        $controller = new ApiKeyController($resourceMock);
        $controller->setResponseHandler(new ResponseHandler(new Serializer()));

        $resourceMock
            ->expects(self::once())
            ->method('getDtoClass')
            ->willReturn($dtoClassMock::class);

        $controller->getDtoClass();
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `getDtoClass` method throws an exception when `Resource` class not returning expected')]
    public function testThatGetDtoClassThrowsAnExceptionIfResourceDoesNotReturnExpectedClass(): void
    {
        $resourceMock = $this->getMockBuilder(ApiKeyResource::class)->disableOriginalConstructor()->getMock();

        $controller = new ApiKeyController($resourceMock);
        $controller->setResponseHandler(new ResponseHandler(new Serializer()));

        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessage(
            'Given DTO class \'stdClass\' is not implementing \'App\DTO\RestDtoInterface\' interface.'
        );

        $resourceMock
            ->expects(self::once())
            ->method('getDtoClass')
            ->willReturn(stdClass::class);

        $controller->getDtoClass();
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `getDtoClass` returns expected when using custom DTO class mapping')]
    public function testThatGetDtoClassWorksAsExpectedWithGivenDtoClasses(): void
    {
        $resourceMock = $this->getMockBuilder(ApiKeyResource::class)->disableOriginalConstructor()->getMock();

        $controller = new ApiKeyController($resourceMock);
        $controller->setResponseHandler(new ResponseHandler(new Serializer()));

        $dtoClasses = [
            'foo' => ApiKey::class,
        ];

        PhpUnitUtil::setProperty('dtoClasses', $dtoClasses, $controller);

        self::assertSame(ApiKey::class, $controller->getDtoClass('foo'));
    }
}
