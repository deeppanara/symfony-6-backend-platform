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
 * @date     01/05/23, 12:13 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /tests/E2E/Controller/v1/UserGroup/AttachUserControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\E2E\Controller\v1\UserGroup;

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
 * Class AttachUserControllerTest
 *
 * @package App\Tests\E2E\Controller\v1\UserGroup
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class AttachUserControllerTest extends WebTestCase
{
    private string $baseUrl = '/v1/user_group';

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
    #[TestDox('Test that `POST /v1/user_group/{id}/user/{id}` request returns `401` for non-logged in user')]
    public function testThatAttachUserGroupReturns401(): void
    {
        $groupUuid = LoadUserGroupData::$uuids['Role-root'];
        $userUuid = LoadUserData::$uuids['john'];

        $client = $this->getTestClient();
        $client->request('POST', $this->baseUrl . '/' . $groupUuid . '/user/' . $userUuid);

        $response = $client->getResponse();
        $content = $response->getContent();

        self::assertNotFalse($content);
        self::assertSame(401, $response->getStatusCode(), $content . "\nResponse:\n" . $response);
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatAttachUserActionReturns403ForInvalidUser')]
    #[TestDox('Test that `POST /v1/user_group/{id}/user/{id}` request returns `403` for not root user `$u` + `$p`')]
    public function testThatAttachUserActionReturns403ForInvalidUser(string $u, string $p): void
    {
        $groupUuid = LoadUserGroupData::$uuids['Role-root'];
        $userUuid = LoadUserData::$uuids['john'];

        $client = $this->getTestClient($u, $p);
        $client->request('POST', $this->baseUrl . '/' . $groupUuid . '/user/' . $userUuid);

        $response = $client->getResponse();
        $content = $response->getContent();

        self::assertNotFalse($content);
        self::assertSame(403, $response->getStatusCode(), "Response:\n" . $response);
        self::assertJsonStringEqualsJsonString(
            '{"message":"Access denied.","code":0,"status":403}',
            $content,
            "Response:\n" . $response
        );
    }

    /**
     * @throws Throwable
     */
    #[DataProvider('dataProviderTestThatAttachUserActionWorksAsExpected')]
    #[TestDox('Test that `POST /v1/user_group/{id}/user/{id}` request returns `$e` for root user')]
    public function testThatAttachUserActionWorksAsExpected(int $e): void
    {
        $groupUuid = LoadUserGroupData::$uuids['Role-root'];
        $userUuid = LoadUserData::$uuids['john'];

        $client = $this->getTestClient('john-root', 'password-root');
        $client->request('POST', $this->baseUrl . '/' . $groupUuid . '/user/' . $userUuid);

        $response = $client->getResponse();
        $content = $response->getContent();

        self::assertNotFalse($content);
        self::assertSame($e, $response->getStatusCode(), "Response:\n" . $response);
        self::assertCount(2, JSON::decode($content));
    }

    /**
     * @return Generator<array{0: string, 1: string}>
     */
    public static function dataProviderTestThatAttachUserActionReturns403ForInvalidUser(): Generator
    {
        yield ['john', 'password'];

        if (getenv('USE_ALL_USER_COMBINATIONS') === 'yes') {
            yield ['john-logged', 'password-logged'];
            yield ['john-api', 'password-api'];
            yield ['john-user', 'password-user'];
            yield ['john-admin', 'password-admin'];
        }

        yield ['john.doe@test.com', 'password'];

        if (getenv('USE_ALL_USER_COMBINATIONS') === 'yes') {
            yield ['john.doe-logged@test.com', 'password-logged'];
            yield ['john.doe-api@test.com', 'password-api'];
            yield ['john.doe-user@test.com', 'password-user'];
            yield ['john.doe-admin@test.com', 'password-admin'];
        }
    }

    /**
     * @return Generator<array{0: int}>
     */
    public static function dataProviderTestThatAttachUserActionWorksAsExpected(): Generator
    {
        yield [201];
        yield [200];
    }
}