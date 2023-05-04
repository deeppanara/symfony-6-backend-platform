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
 * /tests/Integration/Integration/UserRepositoryTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */

namespace App\Tests\Integration\Repository;

use App\Tests\Integration\TestCase\RepositoryTestCase;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Entity\User;
use Platform\Repository\Interfaces\BaseRepositoryInterface;
use Platform\Repository\UserRepository;
use Platform\Resource\UserResource;
use Platform\Rest\Interfaces\RestResourceInterface;

/**
 * Class UserRepositoryTest
 *
 * @package App\Tests\Integration\Repository
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method UserResource getResource()
 * @method UserRepository getRepository()
 */
class UserRepositoryTest extends RepositoryTestCase
{
    /**
     * @var class-string<EntityInterface>
     */
    protected string $entityName = User::class;

    /**
     * @var class-string<BaseRepositoryInterface>
     */
    protected string $repositoryName = UserRepository::class;

    /**
     * @var class-string<RestResourceInterface>
     */
    protected string $resourceName = UserResource::class;

    /**
     * @var array<int, string>
     */
    protected array $associations = [
        'createdBy',
        'updatedBy',
        'userGroups',
        'logsRequest',
        'logsLogin',
        'logsLoginFailure',
    ];

    /**
     * @var array<int, string>
     */
    protected array $searchColumns = [
        'username',
        'firstName',
        'lastName',
        'email',
    ];
}
