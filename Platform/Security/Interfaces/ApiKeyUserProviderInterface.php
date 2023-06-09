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
 * /src/Security/Interfaces/ApiKeyUserProviderInterface.php
 *
 */

namespace Platform\Security\Interfaces;

use Platform\Entity\ApiKey;
use Platform\Repository\ApiKeyRepository;
use Platform\Security\RolesService;

/**
 * Interface ApiKeyUserProviderInterface
 */
interface ApiKeyUserProviderInterface
{
    public function __construct(ApiKeyRepository $apiKeyRepository, RolesService $rolesService);

    /**
     * Method to fetch ApiKey entity for specified token.
     */
    public function getApiKeyForToken(string $token): ?ApiKey;
}
