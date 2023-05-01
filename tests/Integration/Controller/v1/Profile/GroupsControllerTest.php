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
 * @date     01/05/23, 12:11 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /tests/Integration/Controller/v1/Profile/GroupsControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller\v1\Profile;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Controller\v1\Profile\GroupsController;
use Platform\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;
use Throwable;

/**
 * Class GroupsControllerTest
 *
 * @package App\Tests\Integration\Controller\v1\Profile
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class GroupsControllerTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `__invoke(User $loggedInUser)` method calls expected service methods')]
    public function testThatInvokeMethodCallsExpectedMethods(): void
    {
        $user = new User();

        $serializer = $this->getMockBuilder(SerializerInterface::class)->getMock();

        $serializer
            ->expects(self::once())
            ->method('serialize')
            ->with(
                $user->getUserGroups()->toArray(),
                'json',
                [
                    'groups' => 'set.UserProfileGroups',
                ],
            )
            ->willReturn('{}');

        (new GroupsController($serializer))($user);
    }
}
