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
 * /src/Command/User/ListUsersCommand.php
 *
 */

namespace Platform\Command\User;

use Closure;
use Platform\Command\Traits\SymfonyStyleTrait;
use Platform\Entity\User;
use Platform\Entity\UserGroup;
use Platform\Resource\UserResource;
use Platform\Security\RolesService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use function array_map;
use function implode;
use function sprintf;


/**
 *
 */
#[AsCommand(
    name: self::NAME,
    description: 'Console command to list users',
)]
class ListUsersCommand extends Command
{
    use SymfonyStyleTrait;

    final public const NAME = 'user:list';

    public function __construct(
        private readonly UserResource $userResource,
        private readonly RolesService $roles,
    ) {
        parent::__construct();
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     *
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);

        $headers = [
            'Id',
            'Username',
            'Email',
            'Full name',
            'Roles (inherited)',
            'Groups',
        ];

        $io->title('Current users');
        $io->table($headers, $this->getRows());

        return 0;
    }

    /**
     * Getter method for formatted user rows for console table.
     *
     * @return array<int, string>
     *
     * @throws Throwable
     */
    private function getRows(): array
    {
        return array_map(
            $this->getFormatterUser(),
            $this->userResource->find(orderBy: [
                'username' => 'ASC',
            ])
        );
    }

    /**
     * Getter method for user formatter closure. This closure will format
     * single User entity for console table.
     */
    private function getFormatterUser(): Closure
    {
        $userGroupFormatter = static fn (UserGroup $userGroup): string => sprintf(
            '%s (%s)',
            $userGroup->getName(),
            $userGroup->getRole()->getId(),
        );

        return fn (User $user): array => [
            $user->getId(),
            $user->getUsername(),
            $user->getEmail(),
            $user->getFirstName() . ' ' . $user->getLastName(),
            implode(",\n", $this->roles->getInheritedRoles($user->getRoles())),
            implode(",\n", $user->getUserGroups()->map($userGroupFormatter)->toArray()),
        ];
    }
}
