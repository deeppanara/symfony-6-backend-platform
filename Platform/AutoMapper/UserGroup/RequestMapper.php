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
 * /src/AutoMapper/UserGroup/RequestMapper.php
 *
 */

namespace Platform\AutoMapper\UserGroup;

use Platform\AutoMapper\RestRequestMapper;
use Platform\Entity\Role;
use Platform\Resource\RoleResource;
use Throwable;


/**
 *
 */
class RequestMapper extends RestRequestMapper
{
    /**
     * @var array<int, non-empty-string>
     */
    protected static array $properties = [
        'name',
        'role',
    ];

    public function __construct(
        private readonly RoleResource $roleResource,
    ) {
    }

    /**
     * @throws Throwable
     */
    protected function transformRole(string $role): Role
    {
        return $this->roleResource->getReference($role);
    }
}
