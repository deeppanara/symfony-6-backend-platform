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
 * /tests/Integration/Integration/LogLoginRepositoryTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */

namespace App\Tests\Integration\Repository;

use App\Tests\Integration\TestCase\RepositoryTestCase;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Entity\LogLogin;
use Platform\Repository\Interfaces\BaseRepositoryInterface;
use Platform\Repository\LogLoginRepository;
use Platform\Resource\LogLoginResource;
use Platform\Rest\Interfaces\RestResourceInterface;

/**
 * Class LogLoginRepositoryTest
 *
 * @package App\Tests\Integration\Repository
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 *
 * @method LogLoginResource getResource()
 * @method LogLoginRepository getRepository()
 */
class LogLoginRepositoryTest extends RepositoryTestCase
{
    /**
     * @var class-string<EntityInterface>
     */
    protected string $entityName = LogLogin::class;

    /**
     * @var class-string<BaseRepositoryInterface>
     */
    protected string $repositoryName = LogLoginRepository::class;

    /**
     * @var class-string<RestResourceInterface>
     */
    protected string $resourceName = LogLoginResource::class;

    /**
     * @var array<int, string>
     */
    protected array $associations = [
        'user',
    ];
}
