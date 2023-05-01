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
 * /src/Utils/RequestLogger.php
 *
 */

namespace Platform\Utils;

use Platform\Entity\LogRequest;
use Platform\Resource\ApiKeyResource;
use Platform\Resource\LogRequestResource;
use Platform\Resource\UserResource;
use Platform\Utils\Interfaces\RequestLoggerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/*
 *
 * @property  array<int, string> $sensitiveProperties
 */

/**
 *
 */
class RequestLogger implements RequestLoggerInterface
{
    private ?Response $response = null;
    private ?Request $request = null;
    private ?string $userId = null;
    private ?string $apiKeyId = null;
    private bool $mainRequest = false;

    /**
     * @param array<int, string> $sensitiveProperties
     */
    public function __construct(
        private readonly LogRequestResource $logRequestResource,
        private readonly UserResource $userResource,
        private readonly ApiKeyResource $apiKeyResource,
        private readonly LoggerInterface $logger,
        #[Autowire('%env(key:REQUEST_LOG_SENSITIVE_PROPERTIES:json:file:APPLICATION_CONFIG)%')]
        private readonly array $sensitiveProperties,
    ) {
    }

    public function setResponse(Response $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function setApiKeyId(string $apiKeyId): self
    {
        $this->apiKeyId = $apiKeyId;

        return $this;
    }

    public function setMainRequest(bool $mainRequest): self
    {
        $this->mainRequest = $mainRequest;

        return $this;
    }

    public function handle(): void
    {
        // Just check that we have all that we need
        if (!($this->request instanceof Request) || !($this->response instanceof Response)) {
            return;
        }

        try {
            $this->createRequestLogEntry();
        } catch (Throwable $error) {
            $this->logger->error($error->getMessage());
        }
    }

    /**
     * Store request log to database.
     *
     * @throws Throwable
     */
    private function createRequestLogEntry(): void
    {
        /**
         * We want to clear possible existing managements entities before we
         * flush this new `LogRequest` entity to database. This is to prevent
         * not wanted entity state changes to be flushed.
         */
        $this->logRequestResource->getRepository()->getEntityManager()->clear();

        $user = null;
        $apiKey = null;

        if ($this->userId !== null) {
            $user = $this->userResource->getReference($this->userId);
        }

        if ($this->apiKeyId !== null) {
            $apiKey = $this->apiKeyResource->getReference($this->apiKeyId);
        }

        // Create new request log entity
        $entity = new LogRequest(
            $this->sensitiveProperties,
            $this->request,
            $this->response,
            $user,
            $apiKey,
            $this->mainRequest
        );

        $this->logRequestResource->save($entity, true, true);
    }
}
