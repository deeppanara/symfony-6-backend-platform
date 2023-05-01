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
 * /tests/Integration/Security/Provider/SecurityUserFactoryTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Security\Provider;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Entity\User;
use Platform\Entity\User as UserEntity;
use Platform\Repository\UserRepository;
use Platform\Security\ApiKeyUser;
use Platform\Security\Provider\SecurityUserFactory;
use Platform\Security\RolesService;
use Platform\Security\SecurityUser;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

/**
 * Class SecurityUserFactoryTest
 *
 * @package App\Tests\Integration\Security\Provider
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class SecurityUserFactoryTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `loadUserByIdentifier` method throws an exception when user is not found')]
    public function testThatLoadUserByIdentifierThrowsAnExceptionIfUserNotFound(): void
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rolesServiceMock = $this->getMockBuilder(RolesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User not found for UUID:');

        $userRepositoryMock
            ->expects(self::once())
            ->method('loadUserByIdentifier')
            ->with('test_user')
            ->willReturn(null);

        (new SecurityUserFactory($userRepositoryMock, $rolesServiceMock))
            ->loadUserByIdentifier('test_user');
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `loadUserByIdentifier` method returns expected `SecurityUser` instance')]
    public function testThatLoadByUsernameReturnsExpectedSecurityUser(): void
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rolesServiceMock = $this->getMockBuilder(RolesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $user = new User();

        $userRepositoryMock
            ->expects(self::once())
            ->method('loadUserByIdentifier')
            ->with('test_user')
            ->willReturn($user);

        $rolesServiceMock
            ->expects(self::once())
            ->method('getInheritedRoles')
            ->with($user->getRoles())
            ->willReturn(['FOO', 'BAR']);

        $securityUser = (new SecurityUserFactory($userRepositoryMock, $rolesServiceMock))
            ->loadUserByIdentifier('test_user');

        self::assertSame($user->getId(), $securityUser->getUserIdentifier());
        self::assertSame(['FOO', 'BAR'], $securityUser->getRoles());
    }

    #[DataProvider('dataProviderTestThatSupportsMethodsReturnsFalseWithNotSupportedType')]
    #[TestDox('Test that `supportsClass` method returns `false` when using `$input` as input')]
    public function testThatSupportsMethodsReturnsFalseWithNotSupportedType(bool | int | string $input): void
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rolesServiceMock = $this->getMockBuilder(RolesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        self::assertFalse(
            (new SecurityUserFactory($userRepositoryMock, $rolesServiceMock))
                ->supportsClass((string)$input)
        );
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `supportsClass` method returns `true` when using `SecurityUser::class` as input')]
    public function testThatSupportsMethodsReturnsTrueWithSupportedType(): void
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rolesServiceMock = $this->getMockBuilder(RolesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        self::assertTrue(
            (new SecurityUserFactory($userRepositoryMock, $rolesServiceMock))
                ->supportsClass(SecurityUser::class)
        );
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `refreshUser` method throws an exception with not supported user instance')]
    public function testThatRefreshUserThrowsAnExceptionWithNotSupportedUser(): void
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rolesServiceMock = $this->getMockBuilder(RolesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(UnsupportedUserException::class);
        $this->expectExceptionMessageMatches('#^Invalid user class(.*)#');

        (new SecurityUserFactory($userRepositoryMock, $rolesServiceMock))
            ->refreshUser(new InMemoryUser('username', 'password'));
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `refreshUser` method throws an exception when user is not found')]
    public function testThatRefreshUserThrowsAnExceptionIfUserNotFound(): void
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rolesServiceMock = $this->getMockBuilder(RolesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('User not found for UUID:');

        $userRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->willReturn(null);

        (new SecurityUserFactory($userRepositoryMock, $rolesServiceMock))
            ->refreshUser(new SecurityUser(new User()));
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `refreshUser` method returns new instance of `SecurityUser` and it matches with old one')]
    public function testThatRefreshUserReturnsNewInstanceOfSecurityUser(): void
    {
        $userRepositoryMock = $this->getMockBuilder(UserRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rolesServiceMock = $this->getMockBuilder(RolesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $user = new User();
        $securityUser = new SecurityUser($user, ['FOO', 'BAR']);

        $userRepositoryMock
            ->expects(self::once())
            ->method('find')
            ->with($securityUser->getUserIdentifier())
            ->willReturn($user);

        $rolesServiceMock
            ->expects(self::once())
            ->method('getInheritedRoles')
            ->with($user->getRoles())
            ->willReturn(['FOO', 'BAR']);

        $newSecurityUser = (new SecurityUserFactory($userRepositoryMock, $rolesServiceMock))
            ->refreshUser($securityUser);

        self::assertNotSame($securityUser, $newSecurityUser);
        self::assertSame($securityUser->getUserIdentifier(), $newSecurityUser->getUserIdentifier());
        self::assertSame($securityUser->getRoles(), $newSecurityUser->getRoles());
    }

    /**
     * @return Generator<array{0: boolean|string|int}>
     */
    public static function dataProviderTestThatSupportsMethodsReturnsFalseWithNotSupportedType(): Generator
    {
        yield [true];
        yield ['foobar'];
        yield [123];
        yield [stdClass::class];
        yield [UserInterface::class];
        yield [UserEntity::class];
        yield [ApiKeyUser::class];
    }
}
