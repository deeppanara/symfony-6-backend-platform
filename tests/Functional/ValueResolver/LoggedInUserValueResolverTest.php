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
 * /tests/Functional/ValueResolver/LoggedInUserValueResolverTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Functional\ValueResolver;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Entity\User;
use Platform\Repository\UserRepository;
use Platform\Security\Provider\ApiKeyUserProvider;
use Platform\Security\SecurityUser;
use Platform\Security\UserTypeIdentification;
use Platform\ValueResolver\LoggedInUserValueResolver;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Throwable;
use function getenv;
use function iterator_to_array;

/**
 * Class LoggedInUserValueResolverTest
 *
 * @package App\Tests\Functional\ValueResolver
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class LoggedInUserValueResolverTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderValidUsers')]
    #[TestDox('Test that `resolve` method with `$username` input returns expected `User` object.')]
    public function testThatResolveReturnsExpectedUserObject(string $username): void
    {
        $repository = $this->getRepository();

        $user = $repository->loadUserByIdentifier($username, false);

        self::assertNotNull($user);

        $securityUser = new SecurityUser($user);
        $token = new UsernamePasswordToken($securityUser, 'firewall');

        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken($token);

        $apiKeyUserProvider = $this->createMock(ApiKeyUserProvider::class);

        $userTypeIdentification = new UserTypeIdentification($tokenStorage, $repository, $apiKeyUserProvider);

        $resolver = new LoggedInUserValueResolver($userTypeIdentification);
        $metadata = new ArgumentMetadata('loggedInUser', User::class, false, false, null);
        $request = Request::create('/');

        self::assertSame([$user], iterator_to_array($resolver->resolve($request, $metadata)));
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderValidUsers')]
    #[TestDox('Test that integration with argument resolver with `$username` returns expected `User` object.')]
    public function testThatIntegrationWithArgumentResolverReturnsExpectedUser(string $username): void
    {
        $repository = $this->getRepository();

        $user = $repository->loadUserByIdentifier($username, false);

        self::assertNotNull($user);

        $securityUser = new SecurityUser($user);
        $token = new UsernamePasswordToken($securityUser, 'firewall');

        $tokenStorage = new TokenStorage();
        $tokenStorage->setToken($token);

        $apiKeyUserProvider = $this->createMock(ApiKeyUserProvider::class);

        $userTypeIdentification = new UserTypeIdentification($tokenStorage, $repository, $apiKeyUserProvider);

        $argumentResolver = new ArgumentResolver(
            null,
            [new LoggedInUserValueResolver($userTypeIdentification)],
        );

        $closure = static function (User $loggedInUser): void {
            // Do nothing
        };

        self::assertSame([$user], $argumentResolver->getArguments(Request::create('/'), $closure));
    }

    /**
     * @return Generator<array{0: string}>
     */
    public static function dataProviderValidUsers(): Generator
    {
        yield ['john'];

        if (getenv('USE_ALL_USER_COMBINATIONS') === 'yes') {
            yield ['john-logged'];
            yield ['john-api'];
            yield ['john-user'];
            yield ['john-admin'];
            yield ['john-root'];
        }

        yield ['john.doe@test.com'];

        if (getenv('USE_ALL_USER_COMBINATIONS') === 'yes') {
            yield ['john.doe-logged@test.com'];
            yield ['john.doe-api@test.com'];
            yield ['john.doe-user@test.com'];
            yield ['john.doe-admin@test.com'];
            yield ['john.doe-root@test.com'];
        }
    }

    /**
     * @throws Throwable
     */
    private function getRepository(): UserRepository
    {
        static::bootKernel();

        return self::getContainer()->get(UserRepository::class);
    }
}
