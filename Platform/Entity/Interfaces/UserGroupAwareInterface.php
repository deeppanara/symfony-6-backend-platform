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
 * @date     01/05/23, 12:27 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Entity/Interfaces/UserGroupAwareInterface.php
 *
 */

namespace Platform\Entity\Interfaces;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Platform\Entity\UserGroup;

/**
 * Interface UserGroupAwareInterface
 */
interface UserGroupAwareInterface extends EntityInterface
{
    /**
     * @return Collection<int, UserGroup>|ArrayCollection<int, UserGroup>
     */
    public function getUserGroups(): Collection | ArrayCollection;

    /**
     * Method to attach new userGroup to current user OR api key.
     */
    public function addUserGroup(UserGroup $userGroup): mixed;

    /**
     * Method to remove specified userGroup from current user OR api key.
     */
    public function removeUserGroup(UserGroup $userGroup): mixed;

    /**
     * Method to remove all many-to-many userGroup relations from current user
     * OR api key.
     */
    public function clearUserGroups(): mixed;
}
