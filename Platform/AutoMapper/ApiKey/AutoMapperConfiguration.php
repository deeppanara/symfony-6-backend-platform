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
 * /src/AutoMapper/ApiKey/AutoMapperConfiguration.php
 *
 */

namespace Platform\AutoMapper\ApiKey;

use Platform\AutoMapper\RestAutoMapperConfiguration;
use Platform\DTO\ApiKey\ApiKeyCreate;
use Platform\DTO\ApiKey\ApiKeyPatch;
use Platform\DTO\ApiKey\ApiKeyUpdate;


/**
 *
 */
class AutoMapperConfiguration extends RestAutoMapperConfiguration
{
    /**
     * Classes to use specified request mapper.
     *
     * @var array<int, class-string>
     */
    protected static array $requestMapperClasses = [
        ApiKeyCreate::class,
        ApiKeyUpdate::class,
        ApiKeyPatch::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
