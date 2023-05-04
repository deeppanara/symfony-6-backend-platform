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
 * /tests/Integration/Resource/LogLoginResourceTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Resource;

use App\Tests\Integration\TestCase\ResourceTestCase;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Entity\LogLogin;
use Platform\Repository\BaseRepository;
use Platform\Repository\LogLoginRepository;
use Platform\Resource\LogLoginResource;
use Platform\Rest\RestResource;

/**
 * Class LogLoginResourceTest
 *
 * @package App\Tests\Integration\Resource
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class LogLoginResourceTest extends ResourceTestCase
{
    /**
     * @var class-string<EntityInterface>
     */
    protected string $entityClass = LogLogin::class;

    /**
     * @var class-string<BaseRepository>
     */
    protected string $repositoryClass = LogLoginRepository::class;

    /**
     * @var class-string<RestResource>
     */
    protected string $resourceClass = LogLoginResource::class;
}
