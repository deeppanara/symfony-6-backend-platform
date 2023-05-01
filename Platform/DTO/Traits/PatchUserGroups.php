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
 * @date     01/05/23, 12:06 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/DTO/Traits/PatchUserGroups.php
 *
 */

namespace Platform\DTO\Traits;

use Platform\Entity\Interfaces\UserGroupAwareInterface;
use Platform\Entity\UserGroup as UserGroupEntity;
use function array_map;

/**
 * Trait PatchUserGroups
 */
trait PatchUserGroups
{
    /**
     * Method to patch entity user groups.
     *
     * @param array<int, UserGroupEntity> $value
     */
    protected function updateUserGroups(UserGroupAwareInterface $entity, array $value): self
    {
        array_map(
            static fn (UserGroupEntity $userGroup): UserGroupAwareInterface => $entity->addUserGroup($userGroup),
            $value,
        );

        return $this;
    }
}
