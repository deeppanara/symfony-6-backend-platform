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
 * @date     01/05/23, 12:27 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Entity/LogRequest.php
 *
 */

namespace Platform\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use OpenApi\Annotations as OA;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Entity\Traits\LogEntityTrait;
use Platform\Entity\Traits\LogRequestProcessRequestTrait;
use Platform\Entity\Traits\Uuid;
use Ramsey\Uuid\Doctrine\UuidBinaryOrderedTimeType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Annotation\Groups;
use Throwable;
use function mb_strlen;


/**
 *
 */
#[ORM\Entity(
    readOnly: true,
)]
#[ORM\Table(
    name: 'log_request',
)]
#[ORM\Index(
    columns: [
        'user_id',
    ],
    name: 'user_id',
)]
#[ORM\Index(
    columns: [
        'api_key_id',
    ],
    name: 'api_key_id',
)]
#[ORM\Index(
    columns: [
        'date',
    ],
    name: 'request_date',
)]
#[ORM\HasLifecycleCallbacks]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class LogRequest implements EntityInterface
{
    use LogEntityTrait;
    use LogRequestProcessRequestTrait;
    use Uuid;

    /**
     * @OA\Property(type="string", format="uuid")
     */
    #[ORM\Id]
    #[ORM\Column(
        name: 'id',
        type: UuidBinaryOrderedTimeType::NAME,
        unique: true,
    )]
    #[Groups([
        'LogRequest',
        'LogRequest.id',

        'ApiKey.logsRequest',
        'User.logsRequest',
    ])]
    private UuidInterface $id;

    #[ORM\Column(
        name: 'status_code',
        type: Types::INTEGER,
    )]
    #[Groups([
        'LogRequest',
        'LogRequest.statusCode',
    ])]
    private int $statusCode = 0;

    #[ORM\Column(
        name: 'response_content_length',
        type: Types::INTEGER,
    )]
    #[Groups([
        'LogRequest',
        'LogRequest.responseContentLength',
    ])]
    private int $responseContentLength = 0;

    #[ORM\Column(
        name: 'is_main_request',
        type: Types::BOOLEAN,
    )]
    #[Groups([
        'LogRequest',
        'LogRequest.isMainRequest',
    ])]
    private bool $mainRequest;

    /**
     * LogRequest constructor.
     *
     * @param array<int, string> $sensitiveProperties
     *
     * @throws Throwable
     */
    public function __construct(
        private array $sensitiveProperties,
        ?Request $request = null,
        ?Response $response = null,
        #[ORM\ManyToOne(
            targetEntity: User::class,
            inversedBy: 'logsRequest',
        )]
        #[ORM\JoinColumn(
            name: 'user_id',
            onDelete: 'SET NULL',
        )]
        #[Groups([
            'LogRequest.user',
        ])]
        private ?User $user = null,
        #[ORM\ManyToOne(
            targetEntity: ApiKey::class,
            inversedBy: 'logsRequest',
        )]
        #[ORM\JoinColumn(
            name: 'api_key_id',
            onDelete: 'SET NULL',
        )]
        #[Groups([
            'LogRequest.apiKey',
        ])]
        private ?ApiKey $apiKey = null,
        ?bool $mainRequest = null
    ) {
        $this->id = $this->createUuid();
        $this->mainRequest = $mainRequest ?? true;

        $this->processTimeAndDate();

        if ($request !== null) {
            $this->processRequestData($request);
            $this->processRequest($request);
        }

        if ($response !== null) {
            $this->processResponse($response);
        }
    }

    public function getId(): string
    {
        return $this->id->toString();
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponseContentLength(): int
    {
        return $this->responseContentLength;
    }

    public function getApiKey(): ?ApiKey
    {
        return $this->apiKey;
    }

    public function isMainRequest(): bool
    {
        return $this->mainRequest;
    }

    /**
     * @return array<int, string>
     */
    public function getSensitiveProperties(): array
    {
        return $this->sensitiveProperties;
    }

    private function processResponse(Response $response): void
    {
        $content = $response->getContent();

        $this->statusCode = $response->getStatusCode();
        $this->responseContentLength = $content === false ? 0 : mb_strlen($content);
    }
}
