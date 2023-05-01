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
 * /src/Command/User/CreateUserCommand.php
 *
 */

namespace Platform\Command\User;

use Matthias\SymfonyConsoleForm\Console\Helper\FormHelper;
use Platform\Command\HelperConfigure;
use Platform\Command\Traits\ApiKeyUserManagementHelperTrait;
use Platform\Command\Traits\SymfonyStyleTrait;
use Platform\DTO\User\UserCreate as UserDto;
use Platform\Form\Type\Console\UserType;
use Platform\Repository\RoleRepository;
use Platform\Resource\UserGroupResource;
use Platform\Resource\UserResource;
use Platform\Security\RolesService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Throwable;


/**
 *
 */
#[AsCommand(
    name: self::NAME,
    description: 'Console command to create user to database',
)]
class CreateUserCommand extends Command
{
    use ApiKeyUserManagementHelperTrait;
    use SymfonyStyleTrait;

    final public const NAME = 'user:create';

    private const PARAMETER_NAME = 'name';
    private const PARAMETER_DESCRIPTION = 'description';

    /**
     * @var array<int, array<string, string>>
     */
    private static array $commandParameters = [
        [
            self::PARAMETER_NAME => 'username',
            self::PARAMETER_DESCRIPTION => 'Username',
        ],
        [
            self::PARAMETER_NAME => 'firstName',
            self::PARAMETER_DESCRIPTION => 'First name of the user',
        ],
        [
            self::PARAMETER_NAME => 'lastName',
            self::PARAMETER_DESCRIPTION => 'Last name of the user',
        ],
        [
            self::PARAMETER_NAME => 'email',
            self::PARAMETER_DESCRIPTION => 'Email of the user',
        ],
        [
            self::PARAMETER_NAME => 'plainPassword',
            self::PARAMETER_DESCRIPTION => 'Plain password for user',
        ],
        [
            self::PARAMETER_NAME => 'userGroups',
            self::PARAMETER_DESCRIPTION => 'User groups where to attach user',
        ],
    ];

    public function __construct(
        private readonly UserResource $userResource,
        private readonly UserGroupResource $userGroupResource,
        private readonly RolesService $rolesService,
        private readonly RoleRepository $roleRepository,
    ) {
        parent::__construct();
    }

    public function getRolesService(): RolesService
    {
        return $this->rolesService;
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
        $this->checkUserGroups($output, $input->isInteractive(), $io);

        /** @var FormHelper $helper */
        $helper = $this->getHelper('form');

        /** @var UserDto $dto */
        $dto = $helper->interactUsingForm(UserType::class, $input, $output);

        // Create new user
        $this->userResource->create($dto);

        if ($input->isInteractive()) {
            $io->success('User created - have a nice day');
        }

        return 0;
    }

    /**
     * Method to check if database contains user groups, if non exists method
     * will run 'user:create-group' command to create those automatically
     * according to '$this->roles->getRoles()' output. Basically this will
     * automatically create user groups for each role that is defined to
     * application.
     *
     * Also note that if groups are not found method will reset application
     * 'role' table content, so that we can be sure that we can create all
     * groups correctly.
     *
     * @throws Throwable
     */
    private function checkUserGroups(OutputInterface $output, bool $interactive, SymfonyStyle $io): void
    {
        if ($this->userGroupResource->count() !== 0) {
            return;
        }

        if ($interactive) {
            $io->block('User groups are not yet created, creating those now...');
        }

        // Reset roles
        $this->roleRepository->reset();

        // Create user groups for each role
        $this->createUserGroups($output);
    }
}
