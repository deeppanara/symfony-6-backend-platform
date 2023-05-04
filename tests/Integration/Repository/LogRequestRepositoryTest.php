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
 * /tests/Integration/Integration/LogRequestRepositoryTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */

namespace App\Tests\Integration\Repository;

use App\Tests\Integration\TestCase\RepositoryTestCase;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Entity\LogRequest;
use Platform\Repository\Interfaces\BaseRepositoryInterface;
use Platform\Repository\LogRequestRepository;
use Platform\Resource\LogRequestResource;
use Platform\Rest\Interfaces\RestResourceInterface;

/**
 * Class LogRequestRepositoryTest
 *
 * @package App\Tests\Integration\Repository
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method LogRequestResource getResource()
 * @method LogRequestRepository getRepository()
 */
class LogRequestRepositoryTest extends RepositoryTestCase
{
    /**
     * @var class-string<EntityInterface>
     */
    protected string $entityName = LogRequest::class;

    /**
     * @var class-string<BaseRepositoryInterface>
     */
    protected string $repositoryName = LogRequestRepository::class;

    /**
     * @var class-string<RestResourceInterface>
     */
    protected string $resourceName = LogRequestResource::class;

    /**
     * @var array<int, string>
     */
    protected array $associations = [
        'user',
        'apiKey',
    ];
}
