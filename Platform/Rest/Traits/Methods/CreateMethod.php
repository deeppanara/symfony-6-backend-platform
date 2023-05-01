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
 * /src/Rest/Traits/Methods/CreateMethod.php
 *
 */

namespace Platform\Rest\Traits\Methods;

use Platform\DTO\RestDtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Trait CreateMethod
 */
trait CreateMethod
{
    /**
     * Generic 'createMethod' method for REST resources.
     *
     * @param array<int, string>|null $allowedHttpMethods
     *
     * @throws Throwable
     */
    public function createMethod(
        Request $request,
        RestDtoInterface $restDto,
        ?array $allowedHttpMethods = null,
    ): Response {
        $resource = $this->getResourceForMethod($request, $allowedHttpMethods ?? [Request::METHOD_POST]);

        try {
            $data = $resource->create($restDto, true);

            return $this
                ->getResponseHandler()
                ->createResponse($request, $data, $resource, Response::HTTP_CREATED);
        } catch (Throwable $exception) {
            throw $this->handleRestMethodException($exception);
        }
    }
}
