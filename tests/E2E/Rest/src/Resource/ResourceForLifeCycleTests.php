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
 * /tests/E2E/Rest/src/Resource/ResourceForLifeCycleTests.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\E2E\Rest\src\Resource;

use Platform\DTO\RestDtoInterface;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Entity\Role as Entity;
use Platform\Repository\RoleRepository as Repository;
use Platform\Rest\RestResource;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ResourceForLifeCycleTests
 *
 * @package App\Tests\E2E\Rest\src\Resource
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 *
 * @psalm-suppress LessSpecificImplementedReturnType
 * @codingStandardsIgnoreStart
 *
 * @method Entity getReference(string $id)
 * @method Repository getRepository()
 * @method Entity[] find(?array $criteria = null, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $search = null)
 * @method Entity|null findOne(string $id, ?bool $throwExceptionIfNotFound = null)
 * @method Entity|null findOneBy(array $criteria, ?array $orderBy = null, ?bool $throwExceptionIfNotFound = null)
 * @method Entity create(RestDtoInterface $dto, ?bool $flush = null, ?bool $skipValidation = null)
 * @method Entity update(string $id, RestDtoInterface $dto, ?bool $flush = null, ?bool $skipValidation = null)
 * @method Entity patch(string $id, RestDtoInterface $dto, ?bool $flush = null, ?bool $skipValidation = null)
 * @method Entity delete(string $id, ?bool $flush = null)
 * @method Entity save(EntityInterface $entity, ?bool $flush = null, ?bool $skipValidation = null)
 *
 * @codingStandardsIgnoreEnd
 */
class ResourceForLifeCycleTests extends RestResource
{
    public function __construct(
        Repository $repository,
    ) {
        parent::__construct($repository);
    }

    public function afterFindOne(string &$id, ?EntityInterface $entity = null): void
    {
        parent::afterFindOne($id, $entity);

        if ($entity instanceof Entity) {
            $entity->setDescription('some description');
        }

        throw new HttpException(418, 'this should not trigger entity flush to database');
    }
}
