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
 * @date     01/05/23, 12:19 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Utils/Interfaces/RequestLoggerInterface.php
 *
 */

namespace Platform\Utils\Interfaces;

use Platform\Resource\ApiKeyResource;
use Platform\Resource\LogRequestResource;
use Platform\Resource\UserResource;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Interface RequestLoggerInterface
 */
interface RequestLoggerInterface
{
    /**
     * RequestLogger constructor.
     *
     * @codeCoverageIgnore This is needed because variables are multiline
     *
     * @param array<int, string> $sensitiveProperties
     */
    public function __construct(
        LogRequestResource $logRequestResource,
        UserResource $userResource,
        ApiKeyResource $apiKeyResource,
        LoggerInterface $logger,
        array $sensitiveProperties,
    );

    /**
     * Setter for response object.
     */
    public function setResponse(Response $response): self;

    /**
     * Setter for request object.
     */
    public function setRequest(Request $request): self;

    /**
     * Setter method for current user.
     */
    public function setUserId(string $userId): self;

    /**
     * Setter method for current api key
     */
    public function setApiKeyId(string $apiKeyId): self;

    /**
     * Setter method for 'main request' info.
     */
    public function setMainRequest(bool $mainRequest): self;

    /**
     * Method to handle current response and log it to database.
     */
    public function handle(): void;
}
