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
 * /tests/E2E/Controller/HealthzControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\E2E\Controller;

use App\Tests\E2E\TestCase\WebTestCase;
use PHPUnit\Framework\Attributes\TestDox;
use Platform\Resource\LogRequestResource;
use Throwable;

/**
 * Class HealthzControllerTest
 *
 * @package App\Tests\E2E\Controller
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class HealthzControllerTest extends WebTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `GET /healthz` request returns `200`')]
    public function testThatHealthzRouteReturns200(): void
    {
        $client = $this->getTestClient();
        $client->request('GET', '/healthz');

        $response = $client->getResponse();

        self::assertSame(200, $response->getStatusCode(), "Response:\n" . $response);
    }

    /**
     * @throws Throwable
     */
    #[TestDox("Test that `GET /healthz` request doesn't add request log row to database")]
    public function testThatHealthzRouteDoesNotMakeRequestLog(): void
    {
        $resource = self::getContainer()->get(LogRequestResource::class);
        $expectedLogCount = $resource->count();

        $client = $this->getTestClient();
        $client->request('GET', '/healthz');

        self::assertSame($expectedLogCount, $resource->count());
    }
}
