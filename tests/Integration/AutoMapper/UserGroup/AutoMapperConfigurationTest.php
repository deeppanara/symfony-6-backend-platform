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
 * /tests/Integration/AutoMapper/ApiKey/AutoMapperConfigurationTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\AutoMapper\UserGroup;

use App\Tests\Integration\TestCase\RestRequestMapperConfigurationTestCase;
use Platform\AutoMapper\UserGroup\AutoMapperConfiguration;
use Platform\AutoMapper\UserGroup\RequestMapper;
use Platform\DTO\UserGroup\UserGroupCreate;
use Platform\DTO\UserGroup\UserGroupPatch;
use Platform\DTO\UserGroup\UserGroupUpdate;

/**
 * Class AutoMapperConfigurationTest
 *
 * @package App\Tests\Integration\AutoMapper\UserGroup
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class AutoMapperConfigurationTest extends RestRequestMapperConfigurationTestCase
{
    /**
     * @var class-string
     */
    protected string $autoMapperConfiguration = AutoMapperConfiguration::class;

    /**
     * @var class-string
     */
    protected string $requestMapper = RequestMapper::class;

    /**
     * @var array<int, class-string>
     */
    protected static array $requestMapperClasses = [
        UserGroupCreate::class,
        UserGroupUpdate::class,
        UserGroupPatch::class,
    ];
}
