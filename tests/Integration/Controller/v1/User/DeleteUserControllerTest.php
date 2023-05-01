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
 * /tests/Integration/Controller/v1/User/DeleteUserControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller\v1\User;

use App\Tests\Integration\TestCase\RestIntegrationControllerTestCase;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Controller\v1\User\DeleteUserController;
use Platform\Entity\User;
use Platform\Resource\UserResource;
use Platform\Rest\ResponseHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

/**
 * Class DeleteUserControllerTest
 *
 * @package App\Tests\Integration\Controller\v1\User
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 *
 * @method DeleteUserController getController()
 */
class DeleteUserControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var class-string
     */
    protected string $controllerClass = DeleteUserController::class;

    /**
     * @var class-string
     */
    protected string $resourceClass = UserResource::class;

    /**
     * @throws Throwable
     */
    #[TestDox(
        'Test that `__invoke($request, $user, $user)` method trows exception if user is trying to delete himself'
    )]
    public function testThatInvokeMethodThrowsAnExceptionIfUserTriesToDeleteHimself(): void
    {
        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('You cannot remove yourself...');

        $resource = $this->getMockBuilder(UserResource::class)->disableOriginalConstructor()->getMock();

        $resource
            ->expects(self::never())
            ->method('delete');

        $request = Request::create('/');
        $user = new User();

        (new DeleteUserController($resource))($request, $user, $user);
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `__invoke($request, $requestUser, $loggedInUser)` method calls expected service methods')]
    public function testThatInvokeMethodCallsExpectedMethods(): void
    {
        $resource = $this->getMockBuilder(UserResource::class)->disableOriginalConstructor()->getMock();
        $responseHandler = $this->getMockBuilder(ResponseHandler::class)->disableOriginalConstructor()->getMock();

        $request = Request::create('/', 'DELETE');
        $requestUser = new User();
        $loggedInUser = new User();

        $resource
            ->expects(self::once())
            ->method('delete')
            ->with($requestUser->getId())
            ->willReturn($requestUser);

        $responseHandler
            ->expects(self::once())
            ->method('createResponse')
            ->with($request, $requestUser, $resource);

        (new DeleteUserController($resource))
            ->setResponseHandler($responseHandler)
            ->__invoke($request, $requestUser, $loggedInUser);
    }
}
