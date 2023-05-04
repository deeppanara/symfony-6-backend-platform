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
 * /tests/E2E/Controller/v1/User/AttachUserGroupControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\E2E\Controller\v1\User;

use App\Tests\E2E\TestCase\WebTestCase;
use App\Tests\Utils\PhpUnitUtil;
use Generator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\DataFixtures\ORM\LoadUserData;
use Platform\DataFixtures\ORM\LoadUserGroupData;
use Platform\Utils\JSON;
use Throwable;
use function getenv;

/**
 * Class AttachUserGroupControllerTest
 *
 * @package App\Tests\E2E\Controller\v1\User
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class AttachUserGroupControllerTest extends WebTestCase
{
    private string $baseUrl = '/v1/user';

    /**
     * @throws Throwable
     */
    public static function tearDownAfterClass(): void
    {
        self::bootKernel();

        PhpUnitUtil::loadFixtures(self::$kernel);

        self::$kernel->shutdown();

        parent::tearDownAfterClass();
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `POST /v1/user/{id}/group/{id}` request returns `401` for non-logged in user')]
    public function testThatAttachUserGroupReturns401(): void
    {
        $userUuid = LoadUserData::$uuids['john'];
        $groupUuid = LoadUserGroupData::$uuids['Role-root'];

        $client = $this->getTestClient();
        $client->request('POST', $this->baseUrl . '/' . $userUuid . '/group/' . $groupUuid);

        $response = $client->getResponse();
        $content = $response->getContent();

        self::assertNotFalse($content);
        self::assertSame(401, $response->getStatusCode(), $content . "\nResponse:\n" . $response);
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatAttachUserGroupReturns403')]
    #[TestDox('Test that `POST /v1/user/{id}/group/{id}` request returns `403` when using invalid user `$u` + `$p`')]
    public function testThatAttachUserGroupReturns403(string $u, string $p): void
    {
        $userUuid = LoadUserData::$uuids['john'];
        $groupUuid = LoadUserGroupData::$uuids['Role-root'];

        $client = $this->getTestClient($u, $p);
        $client->request('POST', $this->baseUrl . '/' . $userUuid . '/group/' . $groupUuid);

        $response = $client->getResponse();
        $content = $response->getContent();

        self::assertNotFalse($content);
        self::assertSame(403, $response->getStatusCode(), $content . "\nResponse:\n" . $response);
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatAttachUserGroupWorksAsExpected')]
    #[TestDox('Test that `POST /v1/user/{id}/group/{id}` request returns `$expectedStatus` when using root user')]
    public function testThatAttachUserGroupWorksAsExpected(int $expectedStatus): void
    {
        $userUuid = LoadUserData::$uuids['john'];
        $groupUuid = LoadUserGroupData::$uuids['Role-root'];

        $client = $this->getTestClient('john-root', 'password-root');
        $client->request('POST', $this->baseUrl . '/' . $userUuid . '/group/' . $groupUuid);

        $response = $client->getResponse();
        $content = $response->getContent();

        self::assertNotFalse($content);
        self::assertSame($expectedStatus, $response->getStatusCode(), "Response:\n" . $response);
        self::assertCount(1, JSON::decode($content));
    }

    /**
     * @return Generator<array{0: string, 1: string}>
     */
    public static function dataProviderTestThatAttachUserGroupReturns403(): Generator
    {
        if (getenv('USE_ALL_USER_COMBINATIONS') === 'yes') {
            yield ['john', 'password'];
            yield ['john-logged', 'password-logged'];
            yield ['john-api', 'password-api'];
            yield ['john-user', 'password-user'];
        }

        yield ['john-admin', 'password-admin'];

        if (getenv('USE_ALL_USER_COMBINATIONS') === 'yes') {
            yield ['john.doe@test.com', 'password'];
            yield ['john.doe-logged@test.com', 'password-logged'];
            yield ['john.doe-api@test.com', 'password-api'];
            yield ['john.doe-user@test.com', 'password-user'];
        }

        yield ['john.doe-admin@test.com', 'password-admin'];
    }

    /**
     * @return Generator<array{0: int}>
     */
    public static function dataProviderTestThatAttachUserGroupWorksAsExpected(): Generator
    {
        yield [201];
        yield [200];
    }
}
