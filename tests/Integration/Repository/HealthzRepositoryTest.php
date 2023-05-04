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
 * /tests/Integration/Integration/HealthzRepositoryTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */

namespace App\Tests\Integration\Repository;

use App\Tests\Integration\TestCase\RepositoryTestCase;
use Platform\Entity\Healthz;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Repository\HealthzRepository;
use Platform\Repository\Interfaces\BaseRepositoryInterface;
use Platform\Resource\HealthzResource;
use Platform\Rest\Interfaces\RestResourceInterface;

/**
 * Class HealthzRepositoryTest
 *
 * @package App\Tests\Integration\Repository
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method HealthzResource getResource()
 * @method HealthzRepository getRepository()
 */
class HealthzRepositoryTest extends RepositoryTestCase
{
    /**
     * @var class-string<EntityInterface>
     */
    protected string $entityName = Healthz::class;

    /**
     * @var class-string<BaseRepositoryInterface>
     */
    protected string $repositoryName = HealthzRepository::class;

    /**
     * @var class-string<RestResourceInterface>
     */
    protected string $resourceName = HealthzResource::class;
}
