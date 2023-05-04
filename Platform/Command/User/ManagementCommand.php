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
 * /src/Command/User/ManagementCommand.php
 *
 */

namespace Platform\Command\User;

use Platform\Command\Traits\ExecuteMultipleCommandTrait;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;


/**
 *
 */
#[AsCommand(
    name: 'user:management',
    description: 'Console command to manage users and user groups',
)]
class ManagementCommand extends Command
{
    use ExecuteMultipleCommandTrait;

    /**
     * ManagementCommand constructor.
     *
     * @throws LogicException
     */
    public function __construct()
    {
        parent::__construct();

        $this->setChoices([
            ListUsersCommand::NAME => 'List users',
            ListUserGroupsCommand::NAME => 'List user groups',
            CreateUserCommand::NAME => 'Create user',
            CreateUserGroupCommand::NAME => 'Create user group',
            EditUserCommand::NAME => 'Edit user',
            EditUserGroupCommand::NAME => 'Edit user group',
            RemoveUserCommand::NAME => 'Remove user',
            RemoveUserGroupCommand::NAME => 'Remove user group',
            '0' => 'Exit',
        ]);
    }
}
