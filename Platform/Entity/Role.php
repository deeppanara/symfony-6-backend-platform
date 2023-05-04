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
 * /src/Entity/Role.php
 *
 */

namespace Platform\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Entity\Traits\Blameable;
use Platform\Entity\Traits\Timestampable;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 *
 */
#[ORM\Entity]
#[ORM\Table(
    name: 'role',
)]
#[ORM\UniqueConstraint(
    name: 'uq_role',
    columns: [
        'role',
    ],
)]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class Role implements EntityInterface
{
    use Blameable;
    use Timestampable;

    #[ORM\Column(
        name: 'description',
        type: Types::TEXT,
    )]
    #[Groups([
        'Role',
        'Role.description',
    ])]
    private string $description = '';

    /**
     * User groups that belongs to this role.
     *
     * @var Collection<int, UserGroup>|ArrayCollection<int, UserGroup>
     */
    #[ORM\OneToMany(
        mappedBy: 'role',
        targetEntity: UserGroup::class,
    )]
    #[Groups([
        'Role.userGroups',
    ])]
    private Collection | ArrayCollection $userGroups;

    public function __construct(
        #[ORM\Id]
        #[ORM\Column(
            name: 'role',
            type: Types::STRING,
            unique: true,
            nullable: false,
        )]
        #[Groups([
            'Role',
            'Role.id',

            'UserGroup.role',

            User::SET_USER_BASIC,
            UserGroup::SET_USER_PROFILE_GROUPS,
            UserGroup::SET_USER_GROUP_BASIC,
        ])]
        private string $id
    ) {
        $this->userGroups = new ArrayCollection();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, UserGroup>|ArrayCollection<int, UserGroup>
     */
    public function getUserGroups(): Collection | ArrayCollection
    {
        return $this->userGroups;
    }
}
