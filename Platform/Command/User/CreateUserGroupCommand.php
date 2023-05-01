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
 * @date     01/05/23, 12:25 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Command/User/CreateUserGroupCommand.php
 *
 */

namespace Platform\Command\User;

use Matthias\SymfonyConsoleForm\Console\Helper\FormHelper;
use Platform\Command\HelperConfigure;
use Platform\Command\Traits\GetApplicationTrait;
use Platform\Command\Traits\SymfonyStyleTrait;
use Platform\DTO\UserGroup\UserGroupCreate as UserGroupDto;
use Platform\Form\Type\Console\UserGroupType;
use Platform\Repository\RoleRepository;
use Platform\Resource\UserGroupResource;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;


/**
 *
 */
#[AsCommand(
    name: self::NAME,
    description: 'Console command to create user groups',
)]
class CreateUserGroupCommand extends Command
{
    use GetApplicationTrait;
    use SymfonyStyleTrait;

    final public const NAME = 'user:create-group';

    /**
     * @var array<int, array<string, string>>
     */
    private static array $commandParameters = [
        [
            'name' => 'name',
            'description' => 'Name of the user group',
        ],
        [
            'name' => 'role',
            'description' => 'Role of the user group',
        ],
    ];

    public function __construct(
        private readonly UserGroupResource $userGroupResource,
        private readonly RoleRepository $roleRepository,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        parent::configure();

        HelperConfigure::configure($this, self::$commandParameters);
    }

    /**
     * @noinspection PhpMissingParentCallCommonInspection
     *
     * @throws Throwable
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = $this->getSymfonyStyle($input, $output);

        // Check that roles exists
        $this->checkRoles($output, $input->isInteractive(), $io);

        /** @var FormHelper $helper */
        $helper = $this->getHelper('form');

        /** @var UserGroupDto $dto */
        $dto = $helper->interactUsingForm(UserGroupType::class, $input, $output);

        // Create new user group
        $this->userGroupResource->create($dto);

        if ($input->isInteractive()) {
            $io->success('User group created - have a nice day');
        }

        return 0;
    }

    /**
     * Method to check if database contains role(s), if non exists method will
     * run 'user:create-roles' command which creates all roles to database so
     * that user groups can be created.
     *
     * @throws Throwable
     */
    private function checkRoles(OutputInterface $output, bool $interactive, SymfonyStyle $io): void
    {
        if ($this->roleRepository->countAdvanced() !== 0) {
            return;
        }

        if ($interactive) {
            $io->block('Roles are not yet created, creating those now...');
        }

        $command = $this->getApplication()->find('user:create-roles');

        $arguments = [
            'command' => 'user:create-roles',
        ];

        $input = new ArrayInput($arguments);

        $command->run($input, $output);
    }
}
