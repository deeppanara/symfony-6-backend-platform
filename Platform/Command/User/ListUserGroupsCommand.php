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
 * /src/Command/User/ListUserGroupsCommand.php
 *
 */

namespace Platform\Command\User;

use Closure;
use Platform\Command\Traits\SymfonyStyleTrait;
use Platform\Entity\User;
use Platform\Entity\UserGroup;
use Platform\Resource\UserGroupResource;
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
    description: 'Console command to list user groups',
)]
class ListUserGroupsCommand extends Command
{
    use SymfonyStyleTrait;

    final public const NAME = 'user:list-groups';

    public function __construct(
        private readonly UserGroupResource $userGroupResource,
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
            'Name',
            'Role',
            'Users',
        ];

        $io->title('Current user groups');
        $io->table($headers, $this->getRows());

        return 0;
    }

    /**
     * Getter method for formatted user group rows for console table.
     *
     * @return array<int, string>
     *
     * @throws Throwable
     */
    private function getRows(): array
    {
        return array_map(
            $this->getFormatterUserGroup(),
            $this->userGroupResource->find(orderBy: [
                'name' => 'ASC',
            ])
        );
    }

    /**
     * Getter method for user group formatter closure. This closure will
     * format single UserGroup entity for console table.
     */
    private function getFormatterUserGroup(): Closure
    {
        $userFormatter = static fn (User $user): string => sprintf(
            '%s %s <%s>',
            $user->getFirstName(),
            $user->getLastName(),
            $user->getEmail(),
        );

        return static fn (UserGroup $userGroup): array => [
            $userGroup->getId(),
            $userGroup->getName(),
            $userGroup->getRole()->getId(),
            implode(",\n", $userGroup->getUsers()->map($userFormatter)->toArray()),
        ];
    }
}
