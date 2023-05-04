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
use Platform\Entity\ApiKey;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Repository\ApiKeyRepository;
use Platform\Repository\Interfaces\BaseRepositoryInterface;
use Platform\Resource\ApiKeyResource;
use Platform\Rest\Interfaces\RestResourceInterface;

/**
 * Class ApiKeyRepositoryTest
 *
 * @package App\Tests\Integration\Repository
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method ApiKeyResource getResource()
 * @method ApiKeyRepository getRepository()
 */
class ApiKeyRepositoryTest extends RepositoryTestCase
{
    /**
     * @var class-string<EntityInterface>
     */
    protected string $entityName = ApiKey::class;

    /**
     * @var class-string<BaseRepositoryInterface>
     */
    protected string $repositoryName = ApiKeyRepository::class;

    /**
     * @var class-string<RestResourceInterface>
     */
    protected string $resourceName = ApiKeyResource::class;

    /**
     * @var array<int, string>
     */
    protected array $associations = [
        'userGroups',
        'logsRequest',
        'createdBy',
        'updatedBy',
    ];
}
