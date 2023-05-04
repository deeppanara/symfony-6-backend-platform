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
 * /src/DataFixtures/ORM/LoadRoleData.php
 *
 */

namespace Platform\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Platform\Entity\Role;
use Platform\Security\Interfaces\RolesServiceInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Throwable;
use function array_map;

/*
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */

/**
 *
 */
#[AutoconfigureTag('doctrine.fixture.orm')]
final class LoadRoleData extends Fixture implements OrderedFixtureInterface
{
    public function __construct(
        private readonly RolesServiceInterface $rolesService,
    ) {
    }

    /**
     * @throws Throwable
     */
    public function load(ObjectManager $manager): void
    {
        // Create entities
        array_map(fn (string $role): bool => $this->createRole($manager, $role), $this->rolesService->getRoles());

        // Flush database changes
        $manager->flush();
    }

    public function getOrder(): int
    {
        return 1;
    }

    /**
     * Method to create and persist role entity to database.
     *
     * @throws Throwable
     */
    private function createRole(ObjectManager $manager, string $role): bool
    {
        // Create new Role entity
        $entity = (new Role($role))
            ->setDescription('Description - ' . $role);

        // Persist entity
        $manager->persist($entity);

        // Create reference for later usage
        $this->addReference('Role-' . $this->rolesService->getShort($role), $entity);

        return true;
    }
}
