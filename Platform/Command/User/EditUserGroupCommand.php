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
 * /src/Command/User/EditUserGroupCommand.php
 *
 */

namespace Platform\Command\User;

use Matthias\SymfonyConsoleForm\Console\Helper\FormHelper;
use Platform\Command\Traits\SymfonyStyleTrait;
use Platform\DTO\UserGroup\UserGroupPatch as UserGroupDto;
use Platform\Entity\UserGroup as UserGroupEntity;
use Platform\Form\Type\Console\UserGroupType;
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
    description: 'Command to edit existing user group',
)]
class EditUserGroupCommand extends Command
{
    use SymfonyStyleTrait;

    final public const NAME = 'user:edit-group';

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
        $userGroup = $this->userHelper->getUserGroup($io, 'Which user group you want to edit?');
        $message = $userGroup instanceof UserGroupEntity ? $this->updateUserGroup($input, $output, $userGroup) : null;

        if ($input->isInteractive()) {
            $io->success($message ?? 'Nothing changed - have a nice day');
        }

        return 0;
    }

    /**
     * Method to update specified user group entity via specified form.
     *
     * @throws Throwable
     */
    protected function updateUserGroup(
        InputInterface $input,
        OutputInterface $output,
        UserGroupEntity $userGroup,
    ): string {
        // Load entity to DTO
        $dtoLoaded = new UserGroupDto();
        $dtoLoaded->load($userGroup);

        /** @var FormHelper $helper */
        $helper = $this->getHelper('form');

        /** @var UserGroupDto $dtoEdit */
        $dtoEdit = $helper->interactUsingForm(
            UserGroupType::class,
            $input,
            $output,
            [
                'data' => $dtoLoaded,
            ]
        );

        // Patch user group
        $this->userGroupResource->patch($userGroup->getId(), $dtoEdit);

        return 'User group updated - have a nice day';
    }
}
