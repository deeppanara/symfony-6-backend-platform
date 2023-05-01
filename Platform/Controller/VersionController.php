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
 * @date     01/05/23, 12:11 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Controller/VersionController.php
 *
 */

namespace Platform\Controller;

use OpenApi\Annotations as OA;
use Platform\Service\Version;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;


/**
 *
 */
#[AsController]
class VersionController
{
    public function __construct(
        private readonly Version $version,
    ) {
    }

    /**
     * Route for get API version.
     *
     * @OA\Get(
     *      operationId="version",
     *      responses={
     *          @OA\Response(
     *               response=200,
     *               description="success",
     *               @OA\Schema(
     *                   type="object",
     *                   example={"version": "1.2.3"},
     *                   @OA\Property(property="version", type="string", description="Version number"),
     *               ),
     *           ),
     *      },
     *  )
     */
    #[Route(
        path: '/version',
        methods: [Request::METHOD_GET],
    )]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'version' => $this->version->get(),
        ]);
    }
}
