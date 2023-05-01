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
 * /tests/E2E/Rest/ResourceLifeCycleTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\E2E\Rest;

use App\Tests\E2E\Rest\src\Resource\ResourceForLifeCycleTests;
use App\Tests\E2E\TestCase\WebTestCase;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Enum\Role;
use Platform\Repository\RoleRepository;
use Throwable;
use function sprintf;

/**
 * Class ResourceLifeCycleTest
 *
 * @package App\Tests\E2E\Rest
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class ResourceLifeCycleTest extends WebTestCase
{
    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatModifiedEntityIsNotFlushedIfLifeCycleMethodThrowsAnException')]
    #[TestDox('Test that modified entity `$role` is not flushed if life cycle method throws exception')]
    public function testThatModifiedEntityIsNotFlushedIfLifeCycleMethodThrowsAnException(string $role): void
    {
        $client = $this->getTestClient();
        $client->request('GET', '/test_lifecycle_behaviour/' . $role);

        $response = $client->getResponse();
        $entity = $this->getRepository()->findOneBy([
            'id' => $role,
        ]);

        self::assertNotNull($entity, sprintf('Role entity for id `%s` not found...', $role));
        self::assertSame(418, $response->getStatusCode(), (string)$response->getContent());
        self::assertSame('Description - ' . $role, $entity->getDescription());
    }

    /**
     * @return Generator<array<int, string>>
     */
    public static function dataProviderTestThatModifiedEntityIsNotFlushedIfLifeCycleMethodThrowsAnException(): Generator
    {
        foreach (Role::cases() as $role) {
            yield [$role->value];
        }
    }

    /**
     * @throws Throwable
     */
    private function getRepository(): RoleRepository
    {
        return self::getContainer()->get(ResourceForLifeCycleTests::class)->getRepository();
    }
}