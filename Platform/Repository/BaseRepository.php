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
 * /src/Repository/BaseRepository.php
 *
 */

namespace Platform\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Repository\Interfaces\BaseRepositoryInterface;
use Platform\Repository\Traits\RepositoryMethodsTrait;
use Platform\Repository\Traits\RepositoryWrappersTrait;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use function array_map;
use function array_unshift;
use function implode;
use function in_array;
use function serialize;
use function sha1;
use function spl_object_hash;


/**
 *
 */
#[AutoconfigureTag('app.rest.repository')]
#[AutoconfigureTag('app.stopwatch')]
abstract class BaseRepository implements BaseRepositoryInterface
{
    use RepositoryMethodsTrait;
    use RepositoryWrappersTrait;

    private const INNER_JOIN = 'innerJoin';
    private const LEFT_JOIN = 'leftJoin';

    /**
     * @psalm-var class-string
     */
    protected static string $entityName;

    /**
     * @var array<int, string>
     */
    protected static array $searchColumns = [];
    protected static EntityManager $entityManager;

    protected ManagerRegistry $managerRegistry;

    /**
     * Joins that need to attach to queries, this is needed for to prevent duplicate joins on those.
     *
     * @var array<string, array<array<int, scalar>>>
     */
    private static array $joins = [
        self::INNER_JOIN => [],
        self::LEFT_JOIN => [],
    ];

    /**
     * @var array<string, array<int, string>>
     */
    private static array $processedJoins = [
        self::INNER_JOIN => [],
        self::LEFT_JOIN => [],
    ];

    /**
     * @var array<int, array{0: callable, 1: array<mixed>}>
     */
    private static array $callbacks = [];

    /**
     * @var array<int, string>
     */
    private static array $processedCallbacks = [];

    /**
     * @psalm-return class-string
     */
    public function getEntityName(): string
    {
        return static::$entityName;
    }

    public function getSearchColumns(): array
    {
        return static::$searchColumns;
    }

    public function save(EntityInterface $entity, ?bool $flush = null): self
    {
        $flush ??= true;

        // Persist on database
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $this;
    }

    public function remove(EntityInterface $entity, ?bool $flush = null): self
    {
        $flush ??= true;

        // Remove from database
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $this;
    }

    public function processQueryBuilder(QueryBuilder $queryBuilder): void
    {
        // Reset processed joins and callbacks
        self::$processedJoins = [
            self::INNER_JOIN => [],
            self::LEFT_JOIN => [],
        ];
        self::$processedCallbacks = [];

        $this->processJoins($queryBuilder);
        $this->processCallbacks($queryBuilder);
    }

    public function addLeftJoin(array $parameters): self
    {
        if ($parameters !== []) {
            $this->addJoinToQuery(self::LEFT_JOIN, $parameters);
        }

        return $this;
    }

    public function addInnerJoin(array $parameters): self
    {
        if ($parameters !== []) {
            $this->addJoinToQuery(self::INNER_JOIN, $parameters);
        }

        return $this;
    }

    public function addCallback(callable $callable, ?array $args = null): self
    {
        $args ??= [];
        $hash = sha1(serialize([spl_object_hash((object)$callable), ...$args]));

        if (!in_array($hash, self::$processedCallbacks, true)) {
            self::$callbacks[] = [$callable, $args];
            self::$processedCallbacks[] = $hash;
        }

        return $this;
    }

    /**
     * Process defined joins for current QueryBuilder instance.
     */
    protected function processJoins(QueryBuilder $queryBuilder): void
    {
        foreach (self::$joins as $joinType => $joins) {
            array_map(
                static fn (array $joinParameters): QueryBuilder => $queryBuilder->{$joinType}(...$joinParameters),
                $joins,
            );

            self::$joins[$joinType] = [];
        }
    }

    /**
     * Process defined callbacks for current QueryBuilder instance.
     */
    protected function processCallbacks(QueryBuilder $queryBuilder): void
    {
        foreach (self::$callbacks as [$callback, $args]) {
            array_unshift($args, $queryBuilder);

            $callback(...$args);
        }

        self::$callbacks = [];
    }

    /**
     * Method to add defined join(s) to current QueryBuilder query. This will
     * keep track of attached join(s) so any of those are not added multiple
     * times to QueryBuilder.
     *
     * @note processJoins() method must be called for joins to actually be
     *       added to QueryBuilder. processQueryBuilder() method calls this
     *       method automatically.
     *
     * @see QueryBuilder::leftJoin()
     * @see QueryBuilder::innerJoin()
     *
     * @param string $type Join type; leftJoin, innerJoin or join
     * @param array<int, scalar> $parameters Query builder join parameters
     */
    private function addJoinToQuery(string $type, array $parameters): void
    {
        $comparison = implode('|', $parameters);

        if (!in_array($comparison, self::$processedJoins[$type], true)) {
            self::$joins[$type][] = $parameters;

            self::$processedJoins[$type][] = $comparison;
        }
    }
}