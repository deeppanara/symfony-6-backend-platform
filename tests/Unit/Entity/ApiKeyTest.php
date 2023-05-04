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
 * /tests/Unit/Entity/ApiKeyTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Unit\Entity;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Entity\ApiKey;
use Platform\Enum\Role;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use function strlen;

/**
 * Class ApiKeyTest
 *
 * @package App\Tests\Unit\Entity
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class ApiKeyTest extends KernelTestCase
{
    #[TestDox('Test that token is generated on creation of ApiKey entity')]
    public function testThatTokenIsGenerated(): void
    {
        self::assertSame(40, strlen((new ApiKey())->getToken()));
    }

    #[TestDox('Test that ApiKey entity has `ROLE_API` role')]
    public function testThatGetRolesContainsExpectedRole(): void
    {
        self::assertContainsEquals(Role::API->value, (new ApiKey())->getRoles());
    }
}
