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
 * /src/Rest/Interfaces/ResponseHandlerInterface.php
 *
 */

namespace Platform\Rest\Interfaces;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Interface ResponseHandlerInterface
 */
interface ResponseHandlerInterface
{
    /**
     * Constants for response output formats.
     */
    // @codeCoverageIgnoreStart
    public const FORMAT_JSON = 'json';
    public const FORMAT_XML = 'xml';
    // @codeCoverageIgnoreEnd

    public function __construct(SerializerInterface $serializer);

    /**
     * Getter for serializer
     */
    public function getSerializer(): SerializerInterface;

    /**
     * Helper method to get serialization context for request.
     *
     * @return array<int|string, mixed>
     */
    public function getSerializeContext(Request $request, ?RestResourceInterface $restResource = null): array;

    /**
     * Helper method to create response for request.
     *
     * @codeCoverageIgnore This is needed because variables are multiline
     *
     * @param array<int|string, bool|array<int, string>>|null $context
     *
     * @throws HttpException
     */
    public function createResponse(
        Request $request,
        mixed $data,
        ?RestResourceInterface $restResource = null,
        ?int $httpStatus = null,
        ?string $format = null,
        ?array $context = null,
    ): Response;

    /**
     * Method to handle form errors.
     *
     * @throws HttpException
     */
    public function handleFormError(FormInterface $form): void;
}
