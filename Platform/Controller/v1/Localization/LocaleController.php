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
 * /src/Controller/v1/Localization/LocaleController.php
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
class LocaleController
{
    public function __construct(
        private readonly Localization $localization,
    ) {
    }

    /**
     * Endpoint action to get supported locales. This is for use to choose what
     * locale your frontend application can use within its number, time, date,
     * datetime, etc. formatting.
     *
     * @OA\Response(
     *      response=200,
     *      description="List of locale strings.",
     *      @OA\Schema(
     *          type="array",
     *          example={"en","fi"},
     *          @OA\Items(type="string"),
     *      ),
     *  )
     */
    #[Route(
        path: '/v1/localization/locale',
        methods: [Request::METHOD_GET],
    )]
    public function __invoke(): JsonResponse
    {
        return new JsonResponse($this->localization->getLocales());
    }
}
