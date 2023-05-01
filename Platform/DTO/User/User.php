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
 * /src/Rest/DTO/User/User.php
 *
 */

namespace Platform\DTO\User;

use Platform\DTO\RestDto;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Entity\Interfaces\UserGroupAwareInterface;
use Platform\Entity\User as Entity;
use Platform\Entity\UserGroup as UserGroupEntity;
use Platform\Enum\Language;
use Platform\Enum\Locale;
use Platform\Service\Localization;
use Symfony\Component\Validator\Constraints as Assert;
use function array_map;

/*
 *
 * @method Entity|EntityInterface update(EntityInterface $entity)
 *
 * @psalm-consistent-constructor
 */

/**
 *
 */
#[\Platform\Validator\Constraints\UniqueEmail]
#[\Platform\Validator\Constraints\UniqueUsername]
class User extends RestDto
{
    /**
     * @var array<string, string>
     */
    protected static array $mappings = [
        'password' => 'updatePassword',
        'userGroups' => 'updateUserGroups',
    ];

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $username = '';

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $firstName = '';

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Length(min: 2, max: 255)]
    protected string $lastName = '';

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[Assert\Email]
    protected string $email = '';

    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected Language $language;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    protected Locale $locale;

    #[Assert\NotBlank]
    #[Assert\NotNull]
    #[\Platform\Validator\Constraints\Timezone]
    protected string $timezone = Localization::DEFAULT_TIMEZONE;

    /**
     * @var UserGroupEntity[]|array<int, UserGroupEntity>
     */
    #[\Platform\Validator\Constraints\EntityReferenceExists(entityClass: UserGroupEntity::class)]
    protected array $userGroups = [];

    protected string $password = '';

    public function __construct()
    {
        $this->language = Language::getDefault();
        $this->locale = Locale::getDefault();
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->setVisited('username');

        $this->username = $username;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->setVisited('firstName');

        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->setVisited('lastName');

        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->setVisited('email');

        $this->email = $email;

        return $this;
    }

    public function getLanguage(): Language
    {
        return $this->language;
    }

    public function setLanguage(Language $language): self
    {
        $this->setVisited('language');

        $this->language = $language;

        return $this;
    }

    public function getLocale(): Locale
    {
        return $this->locale;
    }

    public function setLocale(Locale $locale): self
    {
        $this->setVisited('locale');

        $this->locale = $locale;

        return $this;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): self
    {
        $this->setVisited('timezone');

        $this->timezone = $timezone;

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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(?string $password = null): self
    {
        if ($password !== null) {
            $this->setVisited('password');

            $this->password = $password;
        }

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
            $this->username = $entity->getUsername();
            $this->firstName = $entity->getFirstName();
            $this->lastName = $entity->getLastName();
            $this->email = $entity->getEmail();
            $this->language = $entity->getLanguage();
            $this->locale = $entity->getLocale();
            $this->timezone = $entity->getTimezone();

            /** @var array<int, UserGroupEntity> $groups */
            $groups = $entity->getUserGroups()->toArray();

            $this->userGroups = $groups;
        }

        return $this;
    }

    /**
     * Method to update User entity password.
     */
    protected function updatePassword(Entity $entity, string $value): self
    {
        $entity->setPlainPassword($value);

        return $this;
    }

    /**
     * Method to update User entity user groups.
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