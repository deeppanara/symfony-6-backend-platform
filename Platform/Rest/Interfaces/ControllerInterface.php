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
 * /src/Rest/Interfaces/ControllerInterface.php
 *
 */

namespace Platform\Rest\Interfaces;

use LogicException;
use Platform\Rest\ResponseHandler;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Throwable;
use UnexpectedValueException;

/**
 * Interface ControllerInterface
 */
#[AutoconfigureTag('app.rest.controller')]
interface ControllerInterface
{
    /**
     * Getter method for `resource` service.
     *
     * @throws UnexpectedValueException
     */
    public function getResource(): RestResourceInterface;

    /**
     * Getter method for `ResponseHandler` service.
     *
     * @throws UnexpectedValueException
     */
    public function getResponseHandler(): ResponseHandlerInterface;

    /**
     * Setter method for `ResponseHandler` service, this is called by Symfony
     * DI.
     */
    public function setResponseHandler(ResponseHandler $responseHandler): static;

    /**
     * Getter method for used DTO class for current controller.
     *
     * @throws UnexpectedValueException
     */
    public function getDtoClass(?string $method = null): string;

    /**
     * Method to validate REST trait method.
     *
     * @param array<int, string> $allowedHttpMethods
     *
     * @throws LogicException
     * @throws MethodNotAllowedHttpException
     */
    public function validateRestMethod(Request $request, array $allowedHttpMethods): void;

    /**
     * Method to handle possible REST method trait exception.
     */
    public function handleRestMethodException(Throwable $exception, ?string $id = null): Throwable;

    /**
     * @param array<int, string> $allowedHttpMethods
     */
    public function getResourceForMethod(Request $request, array $allowedHttpMethods): RestResourceInterface;

    /**
     * Method to process current criteria array.
     *
     * @param array<int|string, string|array<mixed>> $criteria
     */
    public function processCriteria(array &$criteria, Request $request, string $method): void;
}
