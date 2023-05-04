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
 * /tests/Integration/Resource/ApiKeyResourceTest.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Resource;

use App\Tests\Integration\TestCase\ResourceTestCase;
use Platform\Entity\ApiKey;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Repository\ApiKeyRepository;
use Platform\Repository\BaseRepository;
use Platform\Resource\ApiKeyResource;
use Platform\Rest\RestResource;

/**
 * Class ApiKeyResourceTest
 *
 * @package App\Tests\Integration\Resource
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class ApiKeyResourceTest extends ResourceTestCase
{
    /**
     * @var class-string<EntityInterface>
     */
    protected string $entityClass = ApiKey::class;

    /**
     * @var class-string<BaseRepository>
     */
    protected string $repositoryClass = ApiKeyRepository::class;

    /**
     * @var class-string<RestResource>
     */
    protected string $resourceClass = ApiKeyResource::class;
}
