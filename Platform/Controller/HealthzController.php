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
 * /src/Controller/HealthzController.php
 *
 */

namespace Platform\Controller;

use OpenApi\Annotations as OA;
use Platform\Rest\Interfaces\ResponseHandlerInterface;
use Platform\Rest\ResponseHandler;
use Platform\Utils\HealthzService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

/**
 *
 */
#[AsController]
class HealthzController
{
    public function __construct(
        private readonly ResponseHandler $responseHandler,
        private readonly HealthzService $healthzService,
    ) {
    }

    /**
     * Route for application health check. This action will make some simple
     * tasks to ensure that application is up and running like expected.
     *
     * @see https://kubernetes.io/docs/tasks/configure-pod-container/configure-liveness-readiness-probes/
     *
     * @OA\Get(
     *     operationId="healthz",
     *     responses={
     *          @OA\Response(
     *              response=200,
     *              description="success",
     *              @OA\Schema(
     *                  type="object",
     *                  example={"timestamp": "2018-01-01T13:08:05+00:00"},
     *                  @OA\Property(property="timestamp", type="string"),
     *              ),
     *          ),
     *     },
     *  )
     *
     * @throws Throwable
     */
    #[Route(
        path: '/healthz',
        methods: [Request::METHOD_GET],
    )]
    public function __invoke(Request $request): Response
    {
        return $this->responseHandler->createResponse(
            $request,
            $this->healthzService->check(),
            format: ResponseHandlerInterface::FORMAT_JSON,
            context: [
                'groups' => [
                    'Healthz.timestamp',
                ],
            ],
        );
    }
}
