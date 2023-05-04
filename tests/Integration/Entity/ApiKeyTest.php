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
 * /tests/Integration/Entity/ApiKeyTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Entity;

use App\Tests\Integration\TestCase\EntityTestCase;
use App\Tests\Utils\StringableArrayObject;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Entity\ApiKey;
use Platform\Enum\Role;
use Platform\Repository\ApiKeyRepository;
use Platform\Security\RolesService;
use Throwable;
use function array_unique;

/**
 * Class ApiKeyTest
 *
 * @package App\Tests\Integration\Entity
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 *
 * @method ApiKey getEntity()
 */
class ApiKeyTest extends EntityTestCase
{
    /**
     * @var class-string
     */
    protected static string $entityName = ApiKey::class;

    /**
     * @throws Throwable
     *
     * @phpstan-param StringableArrayObject<array<int, string>> $expectedRoles
     * @phpstan-param StringableArrayObject<array> $criteria
     * @psalm-param StringableArrayObject $expectedRoles
     * @psalm-param StringableArrayObject $criteria
     */
    #[DataProvider('dataProviderTestThatApiKeyHasExpectedRoles')]
    #[TestDox('Test that `ApiKey` has expected roles `$expectedRoles` with criteria `$criteria`')]
    public function testThatApiKeyHasExpectedRoles(
        StringableArrayObject $expectedRoles,
        StringableArrayObject $criteria
    ): void {
        static::bootKernel();

        $repository = static::getContainer()->get(ApiKeyRepository::class);
        $apiKey = $repository->findOneBy($criteria->getArrayCopy());

        self::assertInstanceOf(ApiKey::class, $apiKey);
        self::assertSame($expectedRoles->getArrayCopy(), $apiKey->getRoles());
    }

    /**
     * @throws Throwable
     *
     * @psalm-return Generator<array{0: StringableArrayObject, 1: StringableArrayObject}>
     * @phpstan-return Generator<array{0: StringableArrayObject<mixed>, 1: StringableArrayObject<mixed>}>
     */
    public static function dataProviderTestThatApiKeyHasExpectedRoles(): Generator
    {
        static::bootKernel();

        $rolesService = static::getContainer()->get(RolesService::class);

        foreach ($rolesService->getRoles() as $role) {
            yield [
                new StringableArrayObject(array_unique([Role::API->value, $role])),
                new StringableArrayObject([
                    'description' => 'ApiKey Description: ' . $rolesService->getShort($role),
                ]),
            ];
        }
    }
}
