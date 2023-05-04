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
 * /tests/Integration/Controller/v1/Role/FindOneRoleControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller\v1\Role;

use App\Tests\Integration\TestCase\RestIntegrationControllerTestCase;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Controller\v1\Role\FindOneRoleController;
use Platform\Entity\Role;
use Platform\Resource\RoleResource;
use Platform\Rest\ResponseHandler;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class FindOneRoleControllerTest
 *
 * @package App\Tests\Integration\Controller\v1\Role
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 *
 * @method FindOneRoleController getController()
 */
class FindOneRoleControllerTest extends RestIntegrationControllerTestCase
{
    /**
     * @var class-string
     */
    protected string $controllerClass = FindOneRoleController::class;

    /**
     * @var class-string
     */
    protected string $resourceClass = RoleResource::class;

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `__invoke($role)` method calls expected service methods')]
    public function testThatInvokeMethodCallsExpectedMethods(): void
    {
        $resource = $this->getMockBuilder(RoleResource::class)->disableOriginalConstructor()->getMock();
        $responseHandler = $this->getMockBuilder(ResponseHandler::class)->disableOriginalConstructor()->getMock();

        $role = new Role('role');
        $request = Request::create('/');

        $resource
            ->expects(self::once())
            ->method('findOne')
            ->with('role', true)
            ->willReturn($role);

        $responseHandler
            ->expects(self::once())
            ->method('createResponse')
            ->with($request, $role, $resource);

        (new FindOneRoleController($resource))
            ->setResponseHandler($responseHandler)
            ->__invoke($request, 'role');
    }
}
