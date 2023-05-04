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
 * /src/Security/UserTypeIdentification.php
 *
 */

namespace Platform\Security;

use Doctrine\ORM\NonUniqueResultException;
use Platform\Entity\ApiKey;
use Platform\Entity\User;
use Platform\Repository\UserRepository;
use Platform\Security\Provider\ApiKeyUserProvider;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 *
 */
class UserTypeIdentification
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly UserRepository $userRepository,
        private readonly ApiKeyUserProvider $apiKeyUserProvider,
    ) {
    }

    /**
     * Helper method to get current logged in ApiKey entity via token storage.
     */
    public function getApiKey(): ?ApiKey
    {
        $apiKeyUser = $this->getApiKeyUser();

        return $apiKeyUser === null
            ? null
            : $this->apiKeyUserProvider->getApiKeyForToken($apiKeyUser->getUserIdentifier());
    }

    /**
     * Helper method to get current logged in User entity via token storage.
     *
     * @throws NonUniqueResultException
     */
    public function getUser(): ?User
    {
        $user = $this->getSecurityUser();

        return $user === null ? null : $this->userRepository->loadUserByIdentifier($user->getUserIdentifier(), true);
    }

    /**
     * Helper method to get user identity object via token storage.
     */
    public function getIdentity(): ?UserInterface
    {
        return $this->getSecurityUser() ?? $this->getApiKeyUser();
    }

    /**
     * Helper method to get current logged in ApiKeyUser via token storage.
     */
    public function getApiKeyUser(): ?ApiKeyUser
    {
        $apiKeyUser = $this->getUserToken();

        return $apiKeyUser instanceof ApiKeyUser ? $apiKeyUser : null;
    }

    /**
     * Helper method to get current logged in SecurityUser via token storage.
     */
    public function getSecurityUser(): ?SecurityUser
    {
        $securityUser = $this->getUserToken();

        return $securityUser instanceof SecurityUser ? $securityUser : null;
    }

    /**
     * Returns a user representation. Can be a UserInterface instance, an
     * object implementing a __toString method, or the username as a regular
     * string.
     */
    private function getUserToken(): UserInterface | null
    {
        $token = $this->tokenStorage->getToken();

        return $token?->getUser();
    }
}
