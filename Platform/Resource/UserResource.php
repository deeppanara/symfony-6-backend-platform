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
 * /src/Resource/UserResource.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@protacon.com>
 */

namespace Platform\Resource;

use Platform\Entity\User as Entity;
use Platform\Entity\UserGroup;
use Platform\Repository\UserRepository as Repository;
use Platform\Rest\RestResource;
use Platform\Security\RolesService;
use Throwable;
use function array_filter;
use function array_values;
use function in_array;

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
class UserResource extends RestResource
{
    public function __construct(
        Repository $repository,
        private readonly RolesService $rolesService,
    ) {
        parent::__construct($repository);
    }

    /**
     * Method to fetch users for specified user group, note that this method will also check user role inheritance so
     * return value will contain all users that belong to specified user group via role inheritance.
     *
     * @return array<int, Entity>
     *
     * @throws Throwable
     */
    public function getUsersForGroup(UserGroup $userGroup): array
    {
        /**
         * Filter method to see if specified user belongs to certain user group.
         *
         * @param Entity $user
         *
         * @return bool
         */
        $filter = fn (Entity $user): bool => in_array(
            $userGroup->getRole()->getId(),
            $this->rolesService->getInheritedRoles($user->getRoles()),
            true
        );

        $users = $this->find();

        return array_values(array_filter($users, $filter));
    }
}
