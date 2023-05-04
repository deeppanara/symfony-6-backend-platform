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
 * /tests/Integration/Controller/v1/UserGroup/UsersControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller\v1\UserGroup;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Controller\v1\UserGroup\UsersController;
use Platform\Entity\Role;
use Platform\Entity\User;
use Platform\Entity\UserGroup;
use Platform\Resource\UserResource;
use Platform\Rest\ResponseHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Throwable;

/**
 * Class UsersControllerTest
 *
 * @package App\Tests\Integration\Controller\v1\UserGroup
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class UsersControllerTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `__invoke(Request $request, UserGroup $userGroup)` method calls expected service methods')]
    public function testThatInvokeMethodCallsExpectedMethods(): void
    {
        $userResource = $this->getMockBuilder(UserResource::class)->disableOriginalConstructor()->getMock();
        $responseHandler = $this->getMockBuilder(ResponseHandler::class)->disableOriginalConstructor()->getMock();

        $request = new Request();
        $userGroup = (new UserGroup())->setRole(new Role('Some Role'));
        $user = (new User())->addUserGroup($userGroup);

        $userResource
            ->expects(self::once())
            ->method('getUsersForGroup')
            ->with($userGroup)
            ->willReturn([$user]);

        $responseHandler
            ->expects(self::once())
            ->method('createResponse')
            ->with($request, [$user], $userResource);

        (new UsersController($userResource, $responseHandler))($request, $userGroup);
    }
}
