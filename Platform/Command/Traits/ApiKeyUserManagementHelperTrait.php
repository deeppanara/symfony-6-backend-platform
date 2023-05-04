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
 * /src/Command/Traits/ApiKeyUserManagementHelperTrait.php
 *
 */

namespace Platform\Command\Traits;

use Platform\Security\RolesService;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * Trait ApiKeyUserManagementHelperTrait
 */
trait ApiKeyUserManagementHelperTrait
{
    use GetApplicationTrait;

    abstract public function getRolesService(): RolesService;

    /**
     * Method to create user groups via existing 'user:create-group' command.
     *
     * @throws Throwable
     */
    protected function createUserGroups(OutputInterface $output): void
    {
        $command = $this->getApplication()->find('user:create-group');

        // Iterate roles and create user group for each one
        foreach ($this->getRolesService()->getRoles() as $role) {
            $arguments = [
                'command' => 'user:create-group',
                '--name' => $this->getRolesService()->getRoleLabel($role),
                '--role' => $role,
                '-n' => true,
            ];

            $input = new ArrayInput($arguments);
            $input->setInteractive(false);

            $command->run($input, $output);
        }
    }
}
