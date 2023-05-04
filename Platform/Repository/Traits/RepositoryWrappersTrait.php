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
 * /src/Repository/Traits/RepositoryWrappersTrait.php
 *
 */

namespace Platform\Repository\Traits;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Doctrine\ORM\QueryBuilder;
use Platform\Rest\UuidHelper;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use UnexpectedValueException;
use function preg_replace;


/**
 *
 */
trait RepositoryWrappersTrait
{
    public function getReference(string $id): ?object
    {
        try {
            $referenceId = UuidHelper::fromString($id);
        } catch (InvalidUuidStringException) {
            $referenceId = $id;
        }

        return $this->getEntityManager()->getReference($this->getEntityName(), $referenceId);
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-return array<string, array<string, mixed>>
     */
    public function getAssociations(): array
    {
        return $this->getClassMetaData()->getAssociationMappings();
    }

    public function getClassMetaData(): ClassMetadataInfo
    {
        return $this->getEntityManager()->getClassMetadata($this->getEntityName());
    }

    public function getEntityManager(): EntityManager
    {
        $manager = $this->managerRegistry->getManagerForClass($this->getEntityName());

        if (!($manager instanceof EntityManager)) {
            throw new UnexpectedValueException(
                'Cannot get entity manager for entity \'' . $this->getEntityName() . '\''
            );
        }

        if ($manager->isOpen() === false) {
            $this->managerRegistry->resetManager();

            $manager = $this->getEntityManager();
        }

        return $manager;
    }

    public function createQueryBuilder(?string $alias = null, ?string $indexBy = null): QueryBuilder
    {
        $alias ??= 'entity';
        $alias = (string)preg_replace('#[\W]#', '', $alias);
        $indexBy = $indexBy !== null ? (string)preg_replace('#[\W]#', '', $indexBy) : null;

        // Create new query builder
        return $this
            ->getEntityManager()
            ->createQueryBuilder()
            ->select($alias)
            ->from($this->getEntityName(), $alias, $indexBy);
    }
}
