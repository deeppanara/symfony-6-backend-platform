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
 * /src/Controller/v1/Localization/TimezoneController.php
 *
 */

namespace Platform\Controller\v1\Localization;

use OpenApi\Annotations as OA;
use Platform\Service\Localization;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Annotation\Route;

/*
 *
 * @OA\Tag(name="Localization")
 */

/**
 *
 */
#[AsController]
class TimeZoneController
{
    public function __construct(
        private readonly Localization $localization,
    ) {
    }

    /**
     * Endpoint action to get list of supported timezones. This is for use to
     * choose what timezone your frontend application can use within its date,
     * time, datetime, etc. formatting.
     *
     * @OA\Response(
     *      response=200,
     *      description="List of timezone objects.",
     *      @OA\Schema(
     *          type="array",
     *          @OA\Items(
     *              type="object",
     *              @OA\Property(
     *                  property="timezone",
     *                  type="string",
     *                  example="Europe",
     *                  description="Africa,America,Antarctica,Arctic,Asia,Atlantic,Australia,Europe,Indian,Pacific,UTC.",
     *              ),
     *              @OA\Property(
     *                  property="identier",
     *                  type="string",
     *                  example="Europe/Helsinki",
     *                  description="Timezone identifier that you can use with other librariers.",
     *              ),
     *              @OA\Property(
     *                  property="offset",
     *                  type="string",
     *                  example="GMT+2:00",
     *                  description="GMT offset of identifier.",
     *              ),
     *              @OA\Property(
     *                  property="value",
     *                  type="string",
     *                  example="Europe/Helsinki",
     *                  description="User friendly value of identifier value eg. '_' characters are replaced by space.",
     *              ),
     *          ),
     *      ),
     *  )
     */
    #[Route(
        path: '/v1/localization/timezone',
        methods: [Request::METHOD_GET],
    )]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse($this->localization->getTimezones());
    }
}
