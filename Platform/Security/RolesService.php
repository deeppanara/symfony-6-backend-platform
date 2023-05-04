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
 * /src/Security/RolesService.php
 *
 */

namespace Platform\Security;

use BackedEnum;
use Platform\Enum\Role;
use Platform\Security\Interfaces\RolesServiceInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;
use function array_map;
use function array_unique;
use function array_values;
use function mb_strtolower;


/**
 *
 */
class RolesService implements RolesServiceInterface
{
    public function __construct(
        private readonly RoleHierarchyInterface $roleHierarchy,
    ) {
    }

    public function getRoles(): array
    {
        return array_map(static fn (BackedEnum $enum): string => $enum->value, Role::cases());
    }

    public function getRoleLabel(string $role): string
    {
        $enum = Role::tryFrom($role);

        return $enum instanceof Role
            ? $enum->label()
            : 'Unknown - ' . $role;
    }

    public function getShort(string $role): string
    {
        $enum = Role::tryFrom($role);

        return $enum instanceof Role
            ? mb_strtolower($enum->name)
            : 'Unknown - ' . $role;
    }

    public function getInheritedRoles(array $roles): array
    {
        return array_values(array_unique($this->roleHierarchy->getReachableRoleNames($roles)));
    }
}
