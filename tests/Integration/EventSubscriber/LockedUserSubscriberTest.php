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
 * /tests/Integration/EventSubscriber/LockedUserSubscriberTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\EventSubscriber;

use Doctrine\Common\Collections\ArrayCollection;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Entity\User;
use Platform\Entity\User as UserEntity;
use Platform\Enum\Language;
use Platform\Enum\Locale;
use Platform\EventSubscriber\LockedUserSubscriber;
use Platform\Repository\UserRepository;
use Platform\Resource\LogLoginFailureResource;
use Platform\Rest\UuidHelper;
use Platform\Security\SecurityUser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Throwable;
use function range;

/**
 * Class LockedUserSubscriberTest
 *
 * @package App\Tests\Integration\EventSubscriber
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class LockedUserSubscriberTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox(
        'Test that `onAuthenticationSuccess` method throws `UnsupportedUserException` when user is not supported'
    )]
    public function testThatOnAuthenticationSuccessThrowsUserNotFoundException(): void
    {
        $this->expectException(UnsupportedUserException::class);
        $this->expectExceptionMessage('Unsupported user.');

        $userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $logLoginFailureResource = $this->getMockBuilder(LogLoginFailureResource::class)
            ->disableOriginalConstructor()->getMock();

        $requestStack = new RequestStack();
        $requestStack->push(new Request());

        $event = new AuthenticationSuccessEvent(
            [],
            new InMemoryUser('username', 'password'),
            new Response()
        );

        (new LockedUserSubscriber($userRepository, $logLoginFailureResource, $requestStack))
            ->onAuthenticationSuccess($event);
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `onAuthenticationSuccess` throws `LockedException` when user is locked')]
    public function testThatOnAuthenticationSuccessThrowsLockedException(): void
    {
        $this->expectException(LockedException::class);
        $this->expectExceptionMessage('Locked account.');

        $userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $logLoginFailureResource = $this->getMockBuilder(LogLoginFailureResource::class)
            ->disableOriginalConstructor()
            ->getMock();
        $user = $this->getMockBuilder(UserEntity::class)->getMock();

        $requestStack = new RequestStack();
        $requestStack->push(new Request());

        $uuid = UuidHelper::getFactory()->uuid1();

        $user
            ->expects(self::once())
            ->method('getLanguage')
            ->willReturn(Language::EN);

        $user
            ->expects(self::once())
            ->method('getLocale')
            ->willReturn(Locale::EN);

        $user
            ->expects(self::exactly(2))
            ->method('getId')
            ->willReturn($uuid->toString());

        $user
            ->expects(self::once())
            ->method('getLogsLoginFailure')
            ->willReturn(new ArrayCollection(range(0, 11)));

        $userRepository
            ->expects(self::once())
            ->method('loadUserByIdentifier')
            ->with($user->getId())
            ->willReturn($user);

        $securityUser = new SecurityUser($user);
        $event = new AuthenticationSuccessEvent([], $securityUser, new Response());

        (new LockedUserSubscriber($userRepository, $logLoginFailureResource, $requestStack))
            ->onAuthenticationSuccess($event);
    }

    /**
     * @throws Throwable
     */
    #[TestDox(
        'Test that `onAuthenticationSuccess` method calls resource service `reset` method when user is not locked'
    )]
    public function testThatOnAuthenticationSuccessResourceResetMethodIsCalled(): void
    {
        $userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $logLoginFailureResource = $this->getMockBuilder(LogLoginFailureResource::class)
            ->disableOriginalConstructor()->getMock();

        $requestStack = new RequestStack();
        $requestStack->push(new Request());

        $user = new UserEntity();

        $userRepository
            ->expects(self::once())
            ->method('loadUserByIdentifier')
            ->with($user->getId())
            ->willReturn($user);

        $logLoginFailureResource
            ->expects(self::once())
            ->method('reset')
            ->with($user);

        $securityUser = new SecurityUser($user);
        $event = new AuthenticationSuccessEvent([], $securityUser, new Response());

        (new LockedUserSubscriber($userRepository, $logLoginFailureResource, $requestStack))
            ->onAuthenticationSuccess($event);
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `LogLoginFailureResource::save` method is not called when user is not found')]
    public function testThatOnAuthenticationFailureTestThatResourceMethodsAreNotCalledWhenWrongUser(): void
    {
        $userRepository = $this->getMockBuilder(UserRepository::class)->disableOriginalConstructor()->getMock();
        $logLoginFailureResource = $this->getMockBuilder(LogLoginFailureResource::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userRepository
            ->expects(self::once())
            ->method('loadUserByIdentifier')
            ->with('test-user')
            ->willReturn(null);

        $logLoginFailureResource
            ->expects(self::never())
            ->method(self::anything());

        $requestStack = new RequestStack();
        $requestStack->push(new Request([
            'username' => 'test-user',
        ]));

        (new LockedUserSubscriber($userRepository, $logLoginFailureResource, $requestStack))
            ->onAuthenticationFailure();
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `LogLoginFailureResource::save` method is called when user is found')]
    public function testThatOnAuthenticationFailureTestThatResourceSaveMethodIsCalled(): void
    {
        $user = new User();

        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $logLoginFailureResource = $this->getMockBuilder(LogLoginFailureResource::class)
            ->disableOriginalConstructor()
            ->getMock();

        $userRepository
            ->expects(self::once())
            ->method('loadUserByIdentifier')
            ->with('john')
            ->willReturn($user);

        $logLoginFailureResource
            ->expects(self::once())
            ->method('save');

        $request = new Request([
            'username' => 'john',
            'password' => 'wrong-password',
        ]);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $logLoginFailureResource
            ->expects(static::once())
            ->method('save');

        (new LockedUserSubscriber($userRepository, $logLoginFailureResource, $requestStack))
            ->onAuthenticationFailure();
    }
}
