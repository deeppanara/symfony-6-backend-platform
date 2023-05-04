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
 * /tests/Integration/Controller/v1/User/UserGroupsControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller\v1\User;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Controller\v1\User\UserGroupsController;
use Platform\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

/**
 * Class UserGroupsControllerTest
 *
 * @package App\Tests\Integration\Controller\v1\User
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class UserGroupsControllerTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `__invoke(User $requestUser)` method calls expected service methods')]
    public function testThatInvokeMethodCallsExpectedMethods(): void
    {
        $user = new User();

        $serializer = $this->getMockBuilder(SerializerInterface::class)->getMock();

        $serializer
            ->expects(self::once())
            ->method('serialize')
            ->with(
                $user->getUserGroups()->getValues(),
                'json',
                [
                    'groups' => ['set.UserGroupBasic'],
                ],
            )
            ->willReturn('[]');

        (new UserGroupsController($serializer))($user);
    }
}
