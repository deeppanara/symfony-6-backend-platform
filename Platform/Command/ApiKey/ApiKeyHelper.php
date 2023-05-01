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
 * /src/Command/ApiKey/ApiKeyHelper.php
 *
 */

namespace Platform\Command\ApiKey;

use Closure;
use Platform\Entity\ApiKey;
use Platform\Resource\ApiKeyResource;
use Platform\Security\RolesService;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;
use function array_map;
use function implode;
use function sprintf;


/**
 *
 */
class ApiKeyHelper
{
    public function __construct(
        private readonly ApiKeyResource $apiKeyResource,
        private readonly RolesService $rolesService,
    ) {
    }

    /**
     * Method to get API key entity. Also note that this may return a null in
     * cases that user do not want to make any changes to API keys.
     *
     * @throws Throwable
     */
    public function getApiKey(SymfonyStyle $io, string $question): ?ApiKey
    {
        $found = false;
        $apiKey = null;

        while ($found !== true) {
            $apiKey = $this->getApiKeyEntity($io, $question);

            if (!$apiKey instanceof ApiKey) {
                break;
            }

            $message = sprintf(
                'Is this the correct API key \'[%s] [%s] %s\'?',
                $apiKey->getId(),
                $apiKey->getToken(),
                $apiKey->getDescription(),
            );

            $found = $io->confirm($message, false);
        }

        return $apiKey ?? null;
    }

    /**
     * Helper method to get "normalized" message for API key. This is used on
     * following cases:
     *  - User changes API key token
     *  - User creates new API key
     *  - User modifies API key
     *  - User removes API key
     *
     * @return array<int, string>
     */
    public function getApiKeyMessage(string $message, ApiKey $apiKey): array
    {
        return [
            $message,
            sprintf(
                "GUID:  %s\nToken: %s",
                $apiKey->getId(),
                $apiKey->getToken(),
            ),
        ];
    }

    /**
     * Method to list ApiKeys where user can select desired one.
     *
     * @throws Throwable
     */
    private function getApiKeyEntity(SymfonyStyle $io, string $question): ?ApiKey
    {
        $choices = [];

        array_map(
            $this->getApiKeyIterator($choices),
            $this->apiKeyResource->find(orderBy: [
                'token' => 'ASC',
            ])
        );

        $choices['Exit'] = 'Exit command';

        return $this->apiKeyResource->findOne((string)$io->choice($question, $choices));
    }

    /**
     * Method to return ApiKeyIterator closure. This will format ApiKey
     * entities for choice list.
     *
     * @param array<string, string> $choices
     */
    private function getApiKeyIterator(array &$choices): Closure
    {
        return function (ApiKey $apiKey) use (&$choices): void {
            $choices[$apiKey->getId()] = sprintf(
                '[Token: %s] %s - Roles: %s',
                $apiKey->getToken(),
                $apiKey->getDescription(),
                implode(', ', $this->rolesService->getInheritedRoles($apiKey->getRoles())),
            );
        };
    }
}