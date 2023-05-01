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
 * /src/Command/User/UserHelper.php
 *
 */

namespace Platform\Command\User;

use Closure;
use Platform\Entity\User;
use Platform\Entity\UserGroup;
use Platform\Resource\UserGroupResource;
use Platform\Resource\UserResource;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;
use function array_map;
use function sprintf;


/**
 *
 */
class UserHelper
{
    public function __construct(
        private readonly UserResource $userResource,
        private readonly UserGroupResource $userGroupResource,
    ) {
    }

    /**
     * Method to get user entity. Also note that this may return a null in
     * cases that user do not want to make any changes to users.
     *
     * @throws Throwable
     */
    public function getUser(SymfonyStyle $io, string $question): ?User
    {
        $found = false;
        $user = null;

        while (!$found) {
            $user = $this->getUserEntity($io, $question);

            if (!$user instanceof User) {
                break;
            }

            $found = $this->isCorrectUser($io, $user);
        }

        return $user;
    }

    /**
     * Method to get user group entity. Also note that this may return a null
     * in cases that user do not want to make any changes to user groups.
     *
     * @throws Throwable
     */
    public function getUserGroup(SymfonyStyle $io, string $question): ?UserGroup
    {
        $found = false;
        $userGroup = null;

        while (!$found) {
            $userGroup = $this->getUserGroupEntity($io, $question);

            if (!$userGroup instanceof UserGroup) {
                break;
            }

            $found = $this->isCorrectUserGroup($io, $userGroup);
        }

        return $userGroup;
    }

    /**
     * Method to get User entity. Within this user will be asked which User
     * entity he/she wants to process with.
     *
     * @throws Throwable
     */
    private function getUserEntity(SymfonyStyle $io, string $question): ?User
    {
        $choices = [];
        $iterator = $this->getUserIterator($choices);

        array_map(
            $iterator,
            $this->userResource->find(orderBy: [
                'username' => 'asc',
            ])
        );

        $choices['Exit'] = 'Exit command';

        return $this->userResource->findOne((string)$io->choice($question, $choices));
    }

    /**
     * Method to get UserGroup entity. Within this user will be asked which
     * UserGroup entity he/she wants to process with.
     *
     * @throws Throwable
     */
    private function getUserGroupEntity(SymfonyStyle $io, string $question): ?UserGroup
    {
        $choices = [];
        $iterator = $this->getUserGroupIterator($choices);

        array_map(
            $iterator,
            $this->userGroupResource->find(orderBy: [
                'name' => 'asc',
            ])
        );

        $choices['Exit'] = 'Exit command';

        return $this->userGroupResource->findOne((string)$io->choice($question, $choices));
    }

    /**
     * Getter method for user formatter closure. This closure will format
     * single User entity for choice list.
     *
     * @param array<string, string> $choices
     */
    private function getUserIterator(array &$choices): Closure
    {
        return static function (User $user) use (&$choices): void {
            $message = sprintf(
                '%s (%s %s <%s>)',
                $user->getUsername(),
                $user->getFirstName(),
                $user->getLastName(),
                $user->getEmail(),
            );

            $choices[$user->getId()] = $message;
        };
    }

    /**
     * Getter method for user group formatter closure. This closure will format
     * single UserGroup entity for choice list.
     *
     * @param array<string, string> $choices
     */
    private function getUserGroupIterator(array &$choices): Closure
    {
        return static function (UserGroup $userGroup) use (&$choices): void {
            $choices[$userGroup->getId()] = sprintf('%s (%s)', $userGroup->getName(), $userGroup->getRole()->getId());
        };
    }

    /**
     * Helper method to confirm user that he/she has chosen correct User
     * entity to process with.
     */
    private function isCorrectUser(SymfonyStyle $io, User $userEntity): bool
    {
        $message = sprintf(
            'Is this the correct  user [%s - %s (%s %s <%s>)]?',
            $userEntity->getId(),
            $userEntity->getUsername(),
            $userEntity->getFirstName(),
            $userEntity->getLastName(),
            $userEntity->getEmail(),
        );

        return $io->confirm($message, false);
    }

    /**
     * Helper method to confirm user that he/she has chosen correct UserGroup
     * entity to process with.
     */
    private function isCorrectUserGroup(SymfonyStyle $io, UserGroup $userGroupEntity): bool
    {
        $message = sprintf(
            'Is this the correct user group [%s - %s (%s)]?',
            $userGroupEntity->getId(),
            $userGroupEntity->getName(),
            $userGroupEntity->getRole()->getId(),
        );

        return $io->confirm($message, false);
    }
}