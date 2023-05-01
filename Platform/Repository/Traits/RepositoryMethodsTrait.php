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
 * /src/Repository/Traits/RepositoryMethodsTrait.php
 *
 */

namespace Platform\Repository\Traits;

use ArrayIterator;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\ORM\TransactionRequiredException;
use InvalidArgumentException;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Rest\RepositoryHelper;
use Platform\Rest\UuidHelper;
use function array_column;
use function assert;

/**
 * Trait RepositoryMethodsTrait
 */
trait RepositoryMethodsTrait
{
    /**
     * @param string   $id
     * @param int|null $lockMode
     * @param int|null $lockVersion
     *
     * @return EntityInterface|null
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws TransactionRequiredException
     */
    public function find(string $id, ?int $lockMode = null, ?int $lockVersion = null): ?EntityInterface
    {
        $output = $this->getEntityManager()->find($this->getEntityName(), $id, $lockMode, $lockVersion);

        return $output instanceof EntityInterface ? $output : null;
    }

    /**
     * @param string          $id
     * @param string|int|null $hydrationMode
     * @return array|EntityInterface|null
     * @throws NonUniqueResultException
     */
    public function findAdvanced(string $id, string | int | null $hydrationMode = null): null | array | EntityInterface
    {
        // Get query builder
        $queryBuilder = $this->getQueryBuilder();

        // Process custom QueryBuilder actions
        $this->processQueryBuilder($queryBuilder);

        $queryBuilder
            ->where('entity.id = :id')
            ->setParameter('id', $id, UuidHelper::getType($id));

        /*
         * This is just to help debug queries
         *
         * dd($queryBuilder->getQuery()->getDQL(), $queryBuilder->getQuery()->getSQL());
         */

        return $queryBuilder->getQuery()->getOneOrNullResult($hydrationMode);
    }

    public function findOneBy(array $criteria, ?array $orderBy = null): ?object
    {
        $repository = $this->getEntityManager()->getRepository($this->getEntityName());

        return $repository->findOneBy($criteria, $orderBy);
    }

    /**
     * {@inheritdoc}
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     * @return array
     * @throws NotSupported
     */
    public function findBy(array $criteria, ?array $orderBy = null, ?int $limit = null, ?int $offset = null): array
    {
        return $this
            ->getEntityManager()
            ->getRepository($this->getEntityName())
            ->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * {@inheritdoc}
     *
     * @return array<int, EntityInterface>
     */
    public function findByAdvanced(
        array $criteria,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null,
        ?array $search = null
    ): array {
        // Get query builder
        $queryBuilder = $this->getQueryBuilder($criteria, $search, $orderBy, $limit, $offset);

        // Process custom QueryBuilder actions
        $this->processQueryBuilder($queryBuilder);

        /*
         * This is just to help debug queries
         *
         * dd($queryBuilder->getQuery()->getDQL(), $queryBuilder->getQuery()->getSQL());
         */
        RepositoryHelper::resetParameterCount();

        $iterator = (new Paginator($queryBuilder, true))->getIterator();

        assert($iterator instanceof ArrayIterator);

        return $iterator->getArrayCopy();
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     * @throws NotSupported
     */
    public function findAll(): array
    {
        return $this
            ->getEntityManager()
            ->getRepository($this->getEntityName())
            ->findAll();
    }

    /**
     * {@inheritdoc}
     *
     * @return array<int, string>
     */
    public function findIds(?array $criteria = null, ?array $search = null): array
    {
        // Get query builder
        $queryBuilder = $this->getQueryBuilder($criteria, $search);

        // Build query
        $queryBuilder
            ->select('entity.id')
            ->distinct();

        // Process custom QueryBuilder actions
        $this->processQueryBuilder($queryBuilder);

        /*
         * This is just to help debug queries
         *
         * dd($queryBuilder->getQuery()->getDQL(), $queryBuilder->getQuery()->getSQL());
         */
        RepositoryHelper::resetParameterCount();

        return array_column($queryBuilder->getQuery()->getArrayResult(), 'id');
    }

    public function countAdvanced(?array $criteria = null, ?array $search = null): int
    {
        // Get query builder
        $queryBuilder = $this->getQueryBuilder($criteria, $search);

        // Build query
        $queryBuilder->select('COUNT(DISTINCT(entity.id))');

        // Process custom QueryBuilder actions
        $this->processQueryBuilder($queryBuilder);

        /*
         * This is just to help debug queries
         *
         * dd($queryBuilder->getQuery()->getDQL(), $queryBuilder->getQuery()->getSQL());
         */
        RepositoryHelper::resetParameterCount();

        return (int)$queryBuilder->getQuery()->getSingleScalarResult();
    }

    public function reset(): int
    {
        // Create query builder
        $queryBuilder = $this->createQueryBuilder();

        // Define delete query
        $queryBuilder->delete();

        // Return deleted row count
        return (int)$queryBuilder->getQuery()->execute();
    }

    /**
     * Helper method to get QueryBuilder for current instance within specified default parameters.
     *
     * @param array<int|string, mixed>|null $criteria
     * @param array<string, string>|null $search
     * @param array<string, string>|null $orderBy
     *
     * @throws InvalidArgumentException
     */
    private function getQueryBuilder(
        ?array $criteria = null,
        ?array $search = null,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null
    ): QueryBuilder {
        // Create new QueryBuilder for this instance
        $queryBuilder = $this->createQueryBuilder();

        // Process normal and search term criteria
        RepositoryHelper::processCriteria($queryBuilder, $criteria);
        RepositoryHelper::processSearchTerms($queryBuilder, $this->getSearchColumns(), $search);
        RepositoryHelper::processOrderBy($queryBuilder, $orderBy);

        // Process limit and offset
        $queryBuilder->setMaxResults($limit);
        $queryBuilder->setFirstResult($offset ?? 0);

        return $queryBuilder;
    }
}
