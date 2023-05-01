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
 * /tests/Integration/EventSubscriber/AuthenticationFailureSubscriberTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\EventSubscriber;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Platform\Entity\User;
use Platform\EventSubscriber\AuthenticationFailureSubscriber;
use Platform\Repository\UserRepository;
use Platform\Security\SecurityUser;
use Platform\Utils\LoginLogger;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Throwable;

/**
 * Class AuthenticationFailureSubscriberTest
 *
 * @package App\Tests\Integration\EventSubscriber
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class AuthenticationFailureSubscriberTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    public function testThatOnAuthenticationFailureCallsExpectedServiceMethodsWhenUserPresent(): void
    {
        $user = (new User())
            ->setUsername('test-user');

        $token = new UsernamePasswordToken(new SecurityUser($user), 'firewall');

        $authenticationException = new AuthenticationException();
        $authenticationException->setToken($token);

        $response = new Response();

        $event = new AuthenticationFailureEvent($authenticationException, $response);

        $loginLogger = $this->getMockBuilder(LoginLogger::class)->disableOriginalConstructor()->getMock();
        $userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();

        $userRepository
            ->expects(self::once())
            ->method('loadUserByIdentifier')
            ->with($user->getId())
            ->willReturn($user);

        $loginLogger
            ->expects(self::once())
            ->method('setUser')
            ->with($user)
            ->willReturn($loginLogger);

        $loginLogger
            ->expects(self::once())
            ->method('process');

        (new AuthenticationFailureSubscriber($loginLogger, $userRepository))
            ->onAuthenticationFailure($event);
    }

    public function testThatOnAuthenticationFailureCallsExpectedServiceMethodsWhenUserNotPresent(): void
    {
        $user = new User();
        $token = new UsernamePasswordToken(new SecurityUser($user), 'firewall');

        $authenticationException = new AuthenticationException();
        $authenticationException->setToken($token);

        $response = new Response();

        $event = new AuthenticationFailureEvent($authenticationException, $response);

        $loginLogger = $this->getMockBuilder(LoginLogger::class)->disableOriginalConstructor()->getMock();
        $userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();

        $userRepository
            ->expects(self::once())
            ->method('loadUserByIdentifier')
            ->with($user->getId())
            ->willReturn(null);

        $loginLogger
            ->expects(self::once())
            ->method('setUser')
            ->with(null)
            ->willReturn($loginLogger);

        $loginLogger
            ->expects(self::once())
            ->method('process');

        $subscriber = new AuthenticationFailureSubscriber($loginLogger, $userRepository);

        try {
            $subscriber->onAuthenticationFailure($event);
        } catch (Throwable) {
        }
    }
}
