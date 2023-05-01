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
 * @date     01/05/23, 12:25 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Security/Provider/ApiKeyUserProvider.php
 *
 */

namespace Platform\Security\Provider;

use Platform\Entity\ApiKey;
use Platform\Repository\ApiKeyRepository;
use Platform\Security\ApiKeyUser;
use Platform\Security\Interfaces\ApiKeyUserProviderInterface;
use Platform\Security\RolesService;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;


/**
 *
 */
class ApiKeyUserProvider implements ApiKeyUserProviderInterface, UserProviderInterface
{
    public function __construct(
        private readonly ApiKeyRepository $apiKeyRepository,
        private readonly RolesService $rolesService,
    ) {
    }

    public function supportsClass(string $class): bool
    {
        return $class === ApiKeyUser::class;
    }

    public function loadUserByIdentifier(string $identifier): ApiKeyUser
    {
        $apiKey = $this->getApiKeyForToken($identifier);

        if ($apiKey === null) {
            throw new UserNotFoundException('API key is not valid');
        }

        return new ApiKeyUser($apiKey, $this->rolesService->getInheritedRoles($apiKey->getRoles()));
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new UnsupportedUserException('API key cannot refresh user');
    }

    public function getApiKeyForToken(string $token): ?ApiKey
    {
        return $this->apiKeyRepository->findOneBy([
            'token' => $token,
        ]);
    }
}
