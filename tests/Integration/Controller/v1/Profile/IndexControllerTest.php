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
 * /tests/Integration/Controller/v1/Profile/IndexControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller\v1\Profile;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Controller\v1\Profile\IndexController;
use Platform\Entity\User;
use Platform\Security\RolesService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

/**
 * Class IndexControllerTest
 *
 * @package App\Tests\Integration\Controller\v1\Profile
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class IndexControllerTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `__invoke(User $loggedInUser)` method calls expected service methods')]
    public function testThatInvokeMethodCallsExpectedMethods(): void
    {
        $user = new User();

        $serializer = $this->getMockBuilder(SerializerInterface::class)->getMock();

        $rolesService = $this->getMockBuilder(RolesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $serializer
            ->expects(self::once())
            ->method('serialize')
            ->with(
                $user,
                'json',
                [
                    'groups' => 'set.UserProfile',
                ],
            )
            ->willReturn('{"roles": ["foo", "bar"]}');

        $rolesService
            ->expects(self::once())
            ->method('getInheritedRoles')
            ->with(['foo', 'bar'])
            ->willReturn(['foo', 'bar']);

        (new IndexController($serializer, $rolesService))($user);
    }
}
