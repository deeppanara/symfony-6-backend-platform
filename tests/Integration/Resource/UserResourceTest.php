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
 * /tests/Integration/Resource/UserResourceTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Resource;

use App\Tests\Integration\TestCase\ResourceTestCase;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Entity\Role;
use Platform\Entity\User;
use Platform\Entity\UserGroup;
use Platform\Repository\BaseRepository;
use Platform\Repository\UserRepository;
use Platform\Resource\UserResource;
use Platform\Rest\RestResource;
use Platform\Security\RolesService;
use Throwable;

/**
 * Class UserResourceTest
 *
 * @package App\Tests\Integration\Resource
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class UserResourceTest extends ResourceTestCase
{
    /**
     * @var class-string<EntityInterface>
     */
    protected string $entityClass = User::class;

    /**
     * @var class-string<BaseRepository>
     */
    protected string $repositoryClass = UserRepository::class;

    /**
     * @var class-string<RestResource>
     */
    protected string $resourceClass = UserResource::class;

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `getUsersForGroup(UserGroup $userGroup)` method calls expected service methods')]
    public function testThatGetUsersForGroupMethodCallsExpectedServiceMethods(): void
    {
        $repository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $rolesService = $this->getMockBuilder(RolesService::class)->disableOriginalConstructor()->getMock();

        $userGroup = (new UserGroup())->setRole(new Role('Some Role'));
        $user = (new User())->addUserGroup($userGroup);

        $repository
            ->expects(self::once())
            ->method('findByAdvanced')
            ->with()
            ->willReturn([$user]);

        $rolesService
            ->expects(self::once())
            ->method('getInheritedRoles')
            ->with(['Some Role'])
            ->willReturn(['Some Role']);

        self::assertSame([$user], (new UserResource($repository, $rolesService))->getUsersForGroup($userGroup));
    }
}
