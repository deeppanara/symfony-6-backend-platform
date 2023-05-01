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
 * /src/Resource/DateDimensionResource.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */

namespace Platform\Resource;

use Platform\Repository\DateDimensionRepository as Repository;
use Platform\Rest\RestResource;

/*
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
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

/**
 *
 */
class DateDimensionResource extends RestResource
{
    public function __construct(
        Repository $repository,
    ) {
        parent::__construct($repository);
    }
}
