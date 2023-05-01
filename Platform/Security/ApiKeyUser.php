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
 * /src/Security/ApiKeyUser.php
 *
 */

namespace Platform\Security;

use Platform\Entity\ApiKey;
use Platform\Enum\Role;
use Platform\Security\Interfaces\ApiKeyUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use function array_unique;


/**
 *
 */
class ApiKeyUser implements ApiKeyUserInterface, UserInterface
{
    private readonly string $identifier;
    private readonly string $apiKeyIdentifier;

    /**
     * @var array<int, string>
     */
    private readonly array $roles;

    /**
     * {@inheritdoc}
     */
    public function __construct(ApiKey $apiKey, array $roles)
    {
        $this->identifier = $apiKey->getToken();
        $this->apiKeyIdentifier = $apiKey->getId();
        $this->roles = array_unique([...$roles, Role::API->value]);
    }

    public function getUserIdentifier(): string
    {
        return $this->identifier;
    }

    public function getApiKeyIdentifier(): string
    {
        return $this->apiKeyIdentifier;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getPassword(): ?string
    {
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @codeCoverageIgnore
     */
    public function eraseCredentials(): void
    {
    }
}
