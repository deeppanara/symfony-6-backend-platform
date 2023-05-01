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
 * @date     01/05/23, 12:17 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /tests/E2E/Controller/VersionControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\E2E\Controller;

use App\Tests\E2E\TestCase\WebTestCase;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Resource\LogRequestResource;
use Platform\Utils\JSON;
use Throwable;
use function file_get_contents;

/**
 * Class VersionControllerTest
 *
 * @package App\Tests\E2E\Controller
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class VersionControllerTest extends WebTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `GET /version` request returns `200`')]
    public function testThatVersionRouteReturns200(): void
    {
        $client = $this->getTestClient();
        $client->request('GET', '/version');

        $response = $client->getResponse();

        self::assertSame(200, $response->getStatusCode(), "Response:\n" . $response);
    }

    /**
     * @throws Throwable
     */
    #[TestDox("Test that `GET /version` request doesn't add request log row to database")]
    public function testThatVersionRouteDoesNotMakeRequestLog(): void
    {
        $resource = self::getContainer()->get(LogRequestResource::class);
        $expectedLogCount = $resource->count();

        $client = $this->getTestClient();
        $client->request('GET', '/version');

        self::assertSame($expectedLogCount, $resource->count());
    }

    /**
     * @throws Throwable
     */
    #[TestDox('Test that `X-API-VERSION` header is added to response with expected value')]
    public function testThatApiVersionIsAddedToResponseHeaders(): void
    {
        $client = $this->getTestClient();
        $client->request('GET', '/version');

        $response = $client->getResponse();
        $version = $response->headers->get('X-API-VERSION');

        self::assertNotNull($version);
        self::assertSame(
            JSON::decode((string)file_get_contents(__DIR__ . '/../../../composer.json'))->version,
            $version,
        );
    }
}
