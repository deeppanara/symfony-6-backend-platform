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
 * /src/Command/User/RemoveUserGroupCommand.php
 *
 */

namespace Platform\Command\User;

use Platform\Command\Traits\SymfonyStyleTrait;
use Platform\Entity\UserGroup;
use Platform\Resource\UserGroupResource;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;


/**
 *
 */
#[AsCommand(
    name: self::NAME,
    description: 'Console command to remove existing user group',
)]
class RemoveUserGroupCommand extends Command
{
    use SymfonyStyleTrait;

    final public const NAME = 'user:remove-group';

    public function __construct(
        private readonly UserGroupResource $userGroupResource,
        private readonly UserHelper $userHelper,
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
        $userGroup = $this->userHelper->getUserGroup($io, 'Which user group you want to remove?');
        $message = $userGroup instanceof UserGroup ? $this->delete($userGroup) : null;

        if ($input->isInteractive()) {
            $io->success($message ?? 'Nothing changed - have a nice day');
        }

        return 0;
    }

    /**
     * @throws Throwable
     */
    private function delete(UserGroup $userGroup): string
    {
        $this->userGroupResource->delete($userGroup->getId());

        return 'User group removed - have a nice day';
    }
}
