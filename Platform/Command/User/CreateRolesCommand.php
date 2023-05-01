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
 * /src/Command/User/CreateRolesCommand.php
 *
 */

namespace Platform\Command\User;

use Doctrine\ORM\EntityManagerInterface;
use Platform\Command\Traits\SymfonyStyleTrait;
use Platform\Entity\Role;
use Platform\Repository\RoleRepository;
use Platform\Security\RolesService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use function array_map;
use function array_sum;
use function sprintf;


/**
 *
 */
#[AsCommand(
    name: self::NAME,
    description: 'Console command to create roles to database',
)]
class CreateRolesCommand extends Command
{
    use SymfonyStyleTrait;

    final public const NAME = 'user:create-roles';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly RoleRepository $roleRepository,
        private readonly RolesService $rolesService,
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

        $created = array_sum(
            array_map(
                fn (string $role): int => $this->createRole($role),
                $this->rolesService->getRoles(),
            ),
        );

        $this->entityManager->flush();

        $removed = $this->clearRoles($this->rolesService->getRoles());

        if ($input->isInteractive()) {
            $message = sprintf(
                'Created total of %d role(s) and removed %d role(s) - have a nice day',
                $created,
                $removed,
            );

            $io->success($message);
        }

        return 0;
    }

    /**
     * Method to check if specified role exists on database and if not create
     * and persist it to database.
     *
     * @throws Throwable
     */
    private function createRole(string $role): int
    {
        $output = 0;

        if ($this->roleRepository->find($role) === null) {
            $entity = new Role($role);

            $this->entityManager->persist($entity);

            $output = 1;
        }

        return $output;
    }

    /**
     * Method to clean existing roles from database that does not really
     * exists.
     *
     * @param array<int, string> $roles
     */
    private function clearRoles(array $roles): int
    {
        return (int)$this->roleRepository->createQueryBuilder('role')
            ->delete()
            ->where('role.id NOT IN(:roles)')
            ->setParameter(':roles', $roles)
            ->getQuery()
            ->execute();
    }
}
