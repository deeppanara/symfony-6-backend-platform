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
 * @date     01/05/23, 12:17 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Security/Interfaces/RolesServiceInterface.php
 *
 */

namespace Platform\Security\Interfaces;

use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

/**
 * Interface RolesServiceInterface
 *
 * @package Security
 */
interface RolesServiceInterface
{
    public function __construct(RoleHierarchyInterface $roleHierarchy);

    /**
     * Getter method to return all roles in single dimensional array.
     *
     * @return array<int, string>
     */
    public function getRoles(): array;

    /**
     * Getter method for role label.
     */
    public function getRoleLabel(string $role): string;

    /**
     * Getter method for short role.
     */
    public function getShort(string $role): string;

    /**
     * Helper method to get inherited roles for given roles.
     *
     * @param array<int, string> $roles
     *
     * @return array<int, string>
     */
    public function getInheritedRoles(array $roles): array;
}
