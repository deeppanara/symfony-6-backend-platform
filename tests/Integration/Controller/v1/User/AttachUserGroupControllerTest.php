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
 * /tests/Integration/Controller/v1/User/AttachUserGroupControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller\v1\User;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Controller\v1\User\AttachUserGroupController;
use Platform\Entity\Role;
use Platform\Entity\User;
use Platform\Entity\UserGroup;
use Platform\Resource\UserGroupResource;
use Platform\Resource\UserResource;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

/**
 * Class AttachUserGroupControllerTest
 *
 * @package App\Tests\Integration\Controller\v1\User
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class AttachUserGroupControllerTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `__invoke($user, $userGroup)` method calls expected service methods')]
    public function testThatInvokeMethodCallsExpectedMethods(): void
    {
        $userResource = $this->getMockBuilder(UserResource::class)->disableOriginalConstructor()->getMock();
        $userGroupResource = $this->getMockBuilder(UserGroupResource::class)->disableOriginalConstructor()->getMock();
        $serializer = $this->getMockBuilder(SerializerInterface::class)->getMock();

        $user = new User();
        $userGroup = (new UserGroup())->setRole(new Role('role'));

        $userResource
            ->expects(self::once())
            ->method('save')
            ->with($user, false)
            ->willReturn($user);

        $userGroupResource
            ->expects(self::once())
            ->method('save')
            ->with($userGroup, true, true)
            ->willReturn($userGroup);

        $serializer
            ->expects(self::once())
            ->method('serialize')
            ->willReturn('[]');

        (new AttachUserGroupController($userResource, $userGroupResource, $serializer))($user, $userGroup);

        self::assertTrue(
            $user->getUserGroups()->contains($userGroup),
            'User entity does not have expected user group',
        );

        self::assertTrue(
            $userGroup->getUsers()->contains($user),
            'UserGroup entity does not have expected user',
        );
    }
}
