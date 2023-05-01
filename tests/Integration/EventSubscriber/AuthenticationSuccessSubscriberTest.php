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
 * @date     01/05/23, 12:25 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /tests/Integration/EventSubscriber/AuthenticationSuccessSubscriberTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Platform\Entity\User;
use Platform\EventSubscriber\AuthenticationSuccessSubscriber;
use Platform\Repository\UserRepository;
use Platform\Security\SecurityUser;
use Platform\Utils\LoginLogger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class AuthenticationSuccessSubscriberTest
 *
 * @package App\Tests\Integration\EventSubscriber
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class AuthenticationSuccessSubscriberTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    public function testThatOnAuthenticationSuccessMethodCallsExpectedLoggerMethods(): void
    {
        $userEntity = (new User())->setUsername('test_user');
        $securityUser = new SecurityUser($userEntity);
        $event = new AuthenticationSuccessEvent([], $securityUser, new Response());

        $loginLogger = $this->getMockBuilder(LoginLogger::class)->disableOriginalConstructor()->getMock();
        $userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();

        $loginLogger
            ->expects(self::once())
            ->method('setUser')
            ->with($userEntity)
            ->willReturn($loginLogger);

        $loginLogger
            ->expects(self::once())
            ->method('process');

        $userRepository
            ->expects(self::once())
            ->method('loadUserByIdentifier')
            ->with($userEntity->getId())
            ->willReturn($userEntity);

        (new AuthenticationSuccessSubscriber($loginLogger, $userRepository))
            ->onAuthenticationSuccess($event);
    }
}
