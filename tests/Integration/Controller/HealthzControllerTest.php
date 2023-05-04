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
 * /tests/Integration/Controller/HealthzControllerTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Controller;

use PHPUnit\Framework\Attributes\TestDox;
use Platform\Controller\HealthzController;
use Platform\Entity\Healthz;
use Platform\Rest\Interfaces\ResponseHandlerInterface;
use Platform\Rest\ResponseHandler;
use Platform\Utils\HealthzService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Throwable;
use function sprintf;

/**
 * Class HealthzControllerTest
 *
 * @package App\Tests\Integration\Controller
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class HealthzControllerTest extends KernelTestCase
{
    /**
     * @throws Throwable
     */
    #[TestDox('Test that `__invoke` method calls expected service methods')]
    public function testThatInvokeMethodIsCallingExpectedMethods(): void
    {
        $request = Request::create('/healthz');
        $healthz = new Healthz();

        $responseHandler = $this->getMockBuilder(ResponseHandler::class)
            ->disableOriginalConstructor()
            ->getMock();

        $healthzService = $this->getMockBuilder(HealthzService::class)
            ->disableOriginalConstructor()
            ->getMock();

        $healthzService
            ->expects(self::once())
            ->method('check')
            ->willReturn($healthz);

        $responseHandler
            ->expects(self::once())
            ->method('createResponse')
            ->with(
                $request,
                $healthz,
                null,
                null,
                ResponseHandlerInterface::FORMAT_JSON,
                [
                    'groups' => [
                        'Healthz.timestamp',
                    ],
                ],
            )
            ->willReturn(
                new JsonResponse(
                    [
                        'timestamp' => $healthz->getTimestamp()->format('c'),
                    ],
                ),
            );

        $response = (new HealthzController($responseHandler, $healthzService))($request);
        $content = $response->getContent();

        self::assertSame(200, $response->getStatusCode());
        self::assertNotFalse($content);
        self::assertJson($content);
        self::assertJsonStringEqualsJsonString(
            sprintf(
                '{"timestamp": "%s"}',
                $healthz->getTimestamp()->format('c'),
            ),
            $content,
        );
    }
}
