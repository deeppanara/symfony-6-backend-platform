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
 * /src/Repository/HealthzRepository.php
 *
 */

namespace Platform\Repository;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Platform\Entity\Healthz as Entity;
use Throwable;

/*
 *
 * @psalm-suppress LessSpecificImplementedReturnType
 * @codingStandardsIgnoreStart
 *
 * @method Entity|null find(string $id, ?int $lockMode = null, ?int $lockVersion = null)
 * @method Entity|null findAdvanced(string $id, string | int | null $hydrationMode = null)
 * @method Entity|null findOneBy(array $criteria, ?array $orderBy = null)
 * @method Entity[] findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null)
 * @method Entity[] findByAdvanced(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null, ?array $search = null)
 * @method Entity[] findAll()
 *
 * @codingStandardsIgnoreEnd
 */

/**
 *
 */
class HealthzRepository extends BaseRepository
{
    /**
     * @psalm-var class-string
     */
    protected static string $entityName = Entity::class;

    public function __construct(
        protected ManagerRegistry $managerRegistry,
    ) {
    }

    /**
     * Method to read value from database
     *
     * @throws NonUniqueResultException
     */
    public function read(): ?Entity
    {
        $query = $this
            ->createQueryBuilder('h')
            ->select('h')
            ->orderBy('h.timestamp', 'DESC')
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * Method to write new value to database.
     *
     * @throws Throwable
     */
    public function create(): Entity
    {
        // Create new entity
        $entity = new Entity();

        // Store entity to database
        $this->save($entity);

        return $entity;
    }

    /**
     * Method to cleanup 'healthz' table.
     *
     * @throws Exception
     */
    public function cleanup(): int
    {
        // Determine date
        $date = (new DateTimeImmutable(timezone: new DateTimeZone('UTC')))
            ->sub(new DateInterval('P7D'));

        // Create query builder
        $queryBuilder = $this
            ->createQueryBuilder('h')
            ->delete()
            ->where('h.timestamp < :timestamp')
            ->setParameter('timestamp', $date, Types::DATETIME_IMMUTABLE);

        // Return deleted row count
        return (int)$queryBuilder->getQuery()->execute();
    }
}
