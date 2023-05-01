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
 * /tests/Integration/Integration/UserRepositoryTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */

namespace App\Tests\Integration\Repository;

use App\Tests\Integration\TestCase\RepositoryTestCase;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Entity\UserGroup;
use Platform\Repository\Interfaces\BaseRepositoryInterface;
use Platform\Repository\UserGroupRepository;
use Platform\Resource\UserGroupResource;
use Platform\Rest\Interfaces\RestResourceInterface;

/**
 * Class UserGroupRepositoryTest
 *
 * @package App\Tests\Integration\Repository
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method UserGroupResource getResource()
 * @method UserGroupRepository getRepository()
 */
class UserGroupRepositoryTest extends RepositoryTestCase
{
    /**
     * @var class-string<EntityInterface>
     */
    protected string $entityName = UserGroup::class;

    /**
     * @var class-string<BaseRepositoryInterface>
     */
    protected string $repositoryName = UserGroupRepository::class;

    /**
     * @var class-string<RestResourceInterface>
     */
    protected string $resourceName = UserGroupResource::class;

    /**
     * @var array<int, string>
     */
    protected array $associations = [
        'role',
        'users',
        'apiKeys',
        'createdBy',
        'updatedBy',
    ];

    /**
     * @var array<int, string>
     */
    protected array $searchColumns = [
        'role',
        'name',
    ];
}