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
 * /tests/Integration/Resource/DateDimensionResourceTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */

namespace App\Tests\Integration\Resource;

use App\Tests\Integration\TestCase\ResourceTestCase;
use Platform\Entity\DateDimension;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Repository\BaseRepository;
use Platform\Repository\DateDimensionRepository;
use Platform\Resource\DateDimensionResource;
use Platform\Rest\RestResource;

/**
 * Class DateDimensionResourceTest
 *
 * @package App\Tests\Integration\Resource
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class DateDimensionResourceTest extends ResourceTestCase
{
    /**
     * @var class-string<EntityInterface>
     */
    protected string $entityClass = DateDimension::class;

    /**
     * @var class-string<BaseRepository>
     */
    protected string $repositoryClass = DateDimensionRepository::class;

    /**
     * @var class-string<RestResource>
     */
    protected string $resourceClass = DateDimensionResource::class;
}
