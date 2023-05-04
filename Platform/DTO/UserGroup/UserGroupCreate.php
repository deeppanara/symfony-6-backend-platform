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
 * /src/Rest/DTO/UserGroup/UserGroupCreate.php
 *
 */

namespace Platform\DTO\UserGroup;

use Platform\Entity\Role;
use Symfony\Component\Validator\Constraints as Assert;


/**
 *
 */
class UserGroupCreate extends UserGroup
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[\Platform\Validator\Constraints\EntityReferenceExists(entityClass: Role::class)]
    protected ?Role $role = null;
}
