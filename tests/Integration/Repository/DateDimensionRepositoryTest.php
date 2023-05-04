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
 * /tests/Integration/Integration/DateDimensionRepositoryTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */

namespace App\Tests\Integration\Repository;

use App\Tests\Integration\TestCase\RepositoryTestCase;
use Platform\Entity\DateDimension;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Repository\DateDimensionRepository;
use Platform\Repository\Interfaces\BaseRepositoryInterface;
use Platform\Resource\DateDimensionResource;
use Platform\Rest\Interfaces\RestResourceInterface;

/**
 * Class DateDimensionRepositoryTest
 *
 * @package App\Tests\Integration\Repository
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method DateDimensionResource getResource()
 * @method DateDimensionRepository getRepository()
 */
class DateDimensionRepositoryTest extends RepositoryTestCase
{
    /**
     * @var class-string<EntityInterface>
     */
    protected string $entityName = DateDimension::class;

    /**
     * @var class-string<BaseRepositoryInterface>
     */
    protected string $repositoryName = DateDimensionRepository::class;

    /**
     * @var class-string<RestResourceInterface>
     */
    protected string $resourceName = DateDimensionResource::class;
}
