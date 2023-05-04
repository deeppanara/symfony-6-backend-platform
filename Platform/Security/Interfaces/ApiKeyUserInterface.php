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
 * /src/Security/Interfaces/ApiKeyUserInterface.php
 *
 */

namespace Platform\Security\Interfaces;

use Platform\Entity\ApiKey;

/**
 * Interface ApiKeyUserInterface
 */
interface ApiKeyUserInterface
{
    /**
     * ApiKeyUser constructor.
     *
     * @param array<int, string> $roles
     */
    public function __construct(ApiKey $apiKey, array $roles);
}
