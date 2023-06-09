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
 * /src/AutoMapper/UserGroup/AutoMapperConfiguration.php
 *
 */

namespace Platform\AutoMapper\UserGroup;

use Platform\AutoMapper\RestAutoMapperConfiguration;
use Platform\DTO\UserGroup\UserGroupCreate;
use Platform\DTO\UserGroup\UserGroupPatch;
use Platform\DTO\UserGroup\UserGroupUpdate;


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
        UserGroupCreate::class,
        UserGroupUpdate::class,
        UserGroupPatch::class,
    ];

    public function __construct(
        RequestMapper $requestMapper,
    ) {
        parent::__construct($requestMapper);
    }
}
