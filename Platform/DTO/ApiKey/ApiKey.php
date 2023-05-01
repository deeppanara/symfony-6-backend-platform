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
 * /src/DTO/ApiKey/ApiKey.php
 *
 */

namespace Platform\DTO\ApiKey;

use Platform\DTO\RestDto;
use Platform\Entity\ApiKey as Entity;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Entity\Interfaces\UserGroupAwareInterface;
use Platform\Entity\UserGroup as UserGroupEntity;
use Symfony\Component\Validator\Constraints as Assert;
use function array_map;

/*
 *
 * @method Entity|EntityInterface update(EntityInterface $entity)
 */

/**
 *
 */
class ApiKey extends RestDto
{
    /**
     * @var array<string, string>
     */
    protected static array $mappings = [
        'userGroups' => 'updateUserGroups',
    ];

    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected string $description = '';

    protected string $token = '';

    /**
     * @var UserGroupEntity[]|array<int, UserGroupEntity>
     */
    #[\Platform\Validator\Constraints\EntityReferenceExists(UserGroupEntity::class)]
    protected array $userGroups = [];

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->setVisited('token');

        $this->token = $token;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->setVisited('description');

        $this->description = $description;

        return $this;
    }

    /**
     * @return array<int, UserGroupEntity>
     */
    public function getUserGroups(): array
    {
        return $this->userGroups;
    }

    /**
     * @param array<int, UserGroupEntity> $userGroups
     */
    public function setUserGroups(array $userGroups): self
    {
        $this->setVisited('userGroups');

        $this->userGroups = $userGroups;

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
            $this->id = $entity->getId();
            $this->token = $entity->getToken();
            $this->description = $entity->getDescription();

            /** @var array<int, UserGroupEntity> $groups */
            $groups = $entity->getUserGroups()->toArray();

            $this->userGroups = $groups;
        }

        return $this;
    }

    /**
     * Method to update ApiKey entity user groups.
     *
     * @param array<int, UserGroupEntity> $value
     */
    protected function updateUserGroups(UserGroupAwareInterface $entity, array $value): self
    {
        $entity->clearUserGroups();

        array_map(
            static fn (UserGroupEntity $userGroup): UserGroupAwareInterface => $entity->addUserGroup($userGroup),
            $value,
        );

        return $this;
    }
}
