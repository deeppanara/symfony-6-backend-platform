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
 * /src/Command/User/RemoveUserCommand.php
 *
 */

namespace Platform\Command\User;

use Platform\Command\Traits\SymfonyStyleTrait;
use Platform\Entity\User;
use Platform\Resource\UserResource;
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
    description: 'Console command to remove existing user',
)]
class RemoveUserCommand extends Command
{
    use SymfonyStyleTrait;

    final public const NAME = 'user:remove';

    public function __construct(
        private readonly UserResource $userResource,
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
        $user = $this->userHelper->getUser($io, 'Which user you want to remove?');
        $message = $user instanceof User ? $this->delete($user) : null;

        if ($input->isInteractive()) {
            $io->success($message ?? 'Nothing changed - have a nice day');
        }

        return 0;
    }

    /**
     * @throws Throwable
     */
    private function delete(User $user): string
    {
        $this->userResource->delete($user->getId());

        return 'User removed - have a nice day';
    }
}
