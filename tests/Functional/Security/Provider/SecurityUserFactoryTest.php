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
 * /tests/Functional/Security/Provider/SecurityUserFactoryTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Functional\Security\Provider;

use App\Tests\Utils\StringableArrayObject;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Entity\User;
use Platform\Repository\UserRepository;
use Platform\Security\Provider\SecurityUserFactory;
use Platform\Security\SecurityUser;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Throwable;

/**
 * Class SecurityUserFactoryTest
 *
 * @package App\Tests\Integration\Security
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class SecurityUserFactoryTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    public function testThatLoadUserByIdentifierThrowsAnExceptionWithInvalidUsername(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->getSecurityUserFactory()->loadUserByIdentifier('foobar');
    }

    /**
     * @phpstan-param StringableArrayObject<array<int, string>> $roles
     * @psalm-param StringableArrayObject $roles
     *
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatLoadUserByIdentifierReturnsExpectedUserInstance')]
    #[TestDox('Test that `loadUserByIdentifier` method with `$username` returns `SecurityUser` with `$roles` roles')]
    public function testThatLoadUserByIdentifierReturnsExpectedUserInstance(
        string $username,
        StringableArrayObject $roles
    ): void {
        $domainUser = $this->getSecurityUserFactory()->loadUserByIdentifier($username);

        self::assertSame($roles->getArrayCopy(), $domainUser->getRoles());
    }

    /**
     * @throws Throwable
     */
    public function testThatRefreshUserThrowsAnExceptionIfUserIsNotFound(): void
    {
        $this->expectException(UserNotFoundException::class);

        $this->getSecurityUserFactory()->refreshUser(new SecurityUser(new User()));
    }

    /**
     * @throws Throwable
     */
    public function testThatRefreshUserReturnsCorrectUser(): void
    {
        $user = $this->getUserRepository()->findOneBy([
            'username' => 'john',
        ]);

        self::assertNotNull($user);

        $securityUser = new SecurityUser($user);

        self::assertSame(
            $user->getId(),
            $this->getSecurityUserFactory()->refreshUser($securityUser)->getUserIdentifier()
        );
    }

    /**
     * @throws Throwable
     */
    public function testThatRefreshUserReturnsANewInstanceOfSecurityUser(): void
    {
        $user = $this->getUserRepository()->findOneBy([
            'username' => 'john',
        ]);

        self::assertNotNull($user);

        $securityUser = new SecurityUser($user);

        self::assertNotSame($securityUser, $this->getSecurityUserFactory()->refreshUser($securityUser));
    }

    /**
     * @throws Throwable
     */
    public function testThatRefreshUserThrowsAnExceptionIfUserClassIsNotSupported(): void
    {
        $this->expectException(UnsupportedUserException::class);
        $this->expectExceptionMessageMatches('#^Invalid user class(.*)#');

        $user = new InMemoryUser('username', 'password');

        $this->getSecurityUserFactory()->refreshUser($user);
    }

    /**
     * @return Generator<array-key, array{0: string, 1: StringableArrayObject}>
     */
    public static function dataProviderTestThatLoadUserByIdentifierReturnsExpectedUserInstance(): Generator
    {
        yield ['john', new StringableArrayObject([])];
        yield ['john-api', new StringableArrayObject(['ROLE_API', 'ROLE_LOGGED'])];
        yield ['john-logged', new StringableArrayObject(['ROLE_LOGGED'])];
        yield ['john-user', new StringableArrayObject(['ROLE_USER', 'ROLE_LOGGED'])];
        yield ['john-admin', new StringableArrayObject(['ROLE_ADMIN', 'ROLE_USER', 'ROLE_LOGGED'])];
        yield ['john-root', new StringableArrayObject(['ROLE_ROOT', 'ROLE_ADMIN', 'ROLE_USER', 'ROLE_LOGGED'])];
    }

    /**
     * @throws Throwable
     */
    private function getSecurityUserFactory(): SecurityUserFactory
    {
        return self::getContainer()->get(SecurityUserFactory::class);
    }

    /**
     * @throws Throwable
     */
    private function getUserRepository(): UserRepository
    {
        return self::getContainer()->get(UserRepository::class);
    }
}
