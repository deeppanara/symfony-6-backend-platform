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
 * /src/Rest/DTO/UserGroup/UserGroup.php
 *
 */

namespace Platform\DTO\UserGroup;

use Platform\DTO\RestDto;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Entity\Role as RoleEntity;
use Platform\Entity\UserGroup as Entity;
use Symfony\Component\Validator\Constraints as Assert;

/*
 *
 * @method Entity|EntityInterface update(EntityInterface $entity)
 */

/**
 *
 */
class UserGroup extends RestDto
{
    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 4, max: 255)]
    protected string $name = '';

    #[\Platform\Validator\Constraints\EntityReferenceExists(entityClass: RoleEntity::class)]
    protected ?RoleEntity $role = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->setVisited('name');

        $this->name = $name;

        return $this;
    }

    public function getRole(): ?RoleEntity
    {
        return $this->role;
    }

    public function setRole(RoleEntity $role): self
    {
        $this->setVisited('role');

        $this->role = $role;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param EntityInterface|Entity $entity
     */
    public function load(EntityInterface $entity): self
    {
        if ($entity instanceof Entity) {
            $this->name = $entity->getName();
            $this->role = $entity->getRole();
        }

        return $this;
    }
}
