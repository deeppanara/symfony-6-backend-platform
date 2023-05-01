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
 * /tests/Integration/Security/Provider/ApiKeyUserProviderTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Security\Provider;

use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Entity\ApiKey;
use Platform\Entity\User as UserEntity;
use Platform\Repository\ApiKeyRepository;
use Platform\Security\ApiKeyUser;
use Platform\Security\Provider\ApiKeyUserProvider;
use Platform\Security\RolesService;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

/**
 * Class ApiKeyUserProviderTest
 *
 * @package App\Tests\Integration\Security\Provider
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class ApiKeyUserProviderTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatSupportClassReturnsExpected')]
    #[TestDox('Test that `supportsClass` method returns `$expected` when using `$input` as input')]
    public function testThatSupportClassReturnsExpected(bool $expected, mixed $input): void
    {
        $apiKeyRepositoryMock = $this->getMockBuilder(ApiKeyRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rolesServiceMock = $this->getMockBuilder(RolesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $provider = new ApiKeyUserProvider($apiKeyRepositoryMock, $rolesServiceMock);

        self::assertSame($expected, $provider->supportsClass((string)$input));
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `refreshUser` method throws an exception')]
    public function testThatRefreshUserThrowsAnException(): void
    {
        $apiKeyRepositoryMock = $this->getMockBuilder(ApiKeyRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rolesServiceMock = $this->getMockBuilder(RolesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(UnsupportedUserException::class);
        $this->expectExceptionMessage('API key cannot refresh user');

        $user = new InMemoryUser('username', 'password');

        (new ApiKeyUserProvider($apiKeyRepositoryMock, $rolesServiceMock))
            ->refreshUser($user);
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `loadUserByIdentifier` method throws an exception when API key is not found')]
    public function testThatLoadUserByIdentifierThrowsAnException(): void
    {
        $apiKeyRepositoryMock = $this->getMockBuilder(ApiKeyRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rolesServiceMock = $this->getMockBuilder(RolesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $apiKeyRepositoryMock
            ->expects(self::once())
            ->method('findOneBy')
            ->with([
                'token' => 'guid',
            ])
            ->willReturn(null);

        $this->expectException(UserNotFoundException::class);
        $this->expectExceptionMessage('API key is not valid');

        (new ApiKeyUserProvider($apiKeyRepositoryMock, $rolesServiceMock))
            ->loadUserByIdentifier('guid');
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `loadUserByIdentifier` method returns expected `ApiKeyUser` instance')]
    public function testThatLoadUserByIdentifierCreatesExpectedApiKeyUser(): void
    {
        $apiKeyRepositoryMock = $this->getMockBuilder(ApiKeyRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rolesServiceMock = $this->getMockBuilder(RolesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $apiKey = new ApiKey();

        $apiKeyRepositoryMock
            ->expects(self::once())
            ->method('findOneBy')
            ->with([
                'token' => 'guid',
            ])
            ->willReturn($apiKey);

        $user = (new ApiKeyUserProvider($apiKeyRepositoryMock, $rolesServiceMock))
            ->loadUserByIdentifier('guid');

        self::assertSame($apiKey->getId(), $user->getApiKeyIdentifier());
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `getApiKeyForToken` method calls expected repository methods')]
    public function testThatGetApiKeyForTokenCallsExpectedRepositoryMethod(): void
    {
        $apiKeyRepositoryMock = $this->getMockBuilder(ApiKeyRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $rolesServiceMock = $this->getMockBuilder(RolesService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $apiKeyRepositoryMock
            ->expects(self::once())
            ->method('findOneBy')
            ->with([
                'token' => 'some_token',
            ])
            ->willReturn(null);

        (new ApiKeyUserProvider($apiKeyRepositoryMock, $rolesServiceMock))
            ->getApiKeyForToken('some_token');
    }

    /**
     * @return Generator<array{0: boolean, 1: boolean|string|int}>
     */
    public static function dataProviderTestThatSupportClassReturnsExpected(): Generator
    {
        yield [false, true];
        yield [false, 'foobar'];
        yield [false, 123];
        yield [false, stdClass::class];
        yield [false, UserInterface::class];
        yield [false, UserEntity::class];
        yield [true, ApiKeyUser::class];
    }
}
