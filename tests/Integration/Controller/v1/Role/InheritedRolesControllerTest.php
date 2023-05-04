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
 * /tests/Integration/Controller/v1/Role/InheritedRolesControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller\v1\Role;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Controller\v1\Role\InheritedRolesController;
use Platform\Entity\Role;
use Platform\Security\RolesService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class InheritedRolesControllerTest
 *
 * @package App\Tests\Integration\Controller\v1\Role
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class InheritedRolesControllerTest extends KernelTestCase
{
    #[TestDox('Test that `__invoke($role)` method calls expected service methods')]
    public function testThatInvokeMethodCallsExpectedMethods(): void
    {
        $rolesService = $this->getMockBuilder(RolesService::class)->disableOriginalConstructor()->getMock();
        $role = new Role('Test');

        $rolesService
            ->expects(self::once())
            ->method('getInheritedRoles')
            ->with([$role->getId()])
            ->willReturn([$role]);

        (new InheritedRolesController($rolesService))($role);
    }
}
