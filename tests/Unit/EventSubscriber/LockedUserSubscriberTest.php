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
 * /tests/Unit/EventSubscriber/LockedUserSubscriberTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Unit\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\EventSubscriber\LockedUserSubscriber;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Class LockedUserSubscriberTest
 *
 * @package App\Tests\Unit\EventSubscriber
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class LockedUserSubscriberTest extends KernelTestCase
{
    #[TestDox('Test that `getSubscribedEvents` method returns expected')]
    public function testThatGetSubscribedEventsReturnsExpected(): void
    {
        $expected = [
            AuthenticationSuccessEvent::class => [
                'onAuthenticationSuccess',
                128,
            ],
            'lexik_jwt_authentication.on_authentication_success' => [
                'onAuthenticationSuccess',
                128,
            ],
            AuthenticationFailureEvent::class => 'onAuthenticationFailure',
            'lexik_jwt_authentication.on_authentication_failure' => 'onAuthenticationFailure',
        ];

        self::assertSame($expected, LockedUserSubscriber::getSubscribedEvents());
    }
}
