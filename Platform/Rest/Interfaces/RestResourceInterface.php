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
 * /src/Rest/Interfaces/RestResourceInterface.php
 *
 */

namespace Platform\Rest\Interfaces;

use Platform\DTO\RestDtoInterface;
use Platform\Entity\Interfaces\EntityInterface;
use Platform\Repository\Interfaces\BaseRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;
use UnexpectedValueException;

/**
 * Interface RestResourceInterface
 */
#[AutoconfigureTag('app.rest.resource')]
#[AutoconfigureTag('app.stopwatch')]
interface RestResourceInterface
{
    /**
     * Getter method for serializer context.
     *
     * @return array<int|string, mixed>
     */
    public function getSerializerContext(): array;

    /**
     * Getter method for entity repository.
     *
     * @throws Throwable
     */
    public function getRepository(): BaseRepositoryInterface;

    /**
     * Getter for used validator.
     */
    public function getValidator(): ValidatorInterface;

    /**
     * Setter for used validator.
     */
    public function setValidator(ValidatorInterface $validator): self;

    /**
     * Getter method for used DTO class for this REST service.
     *
     * @throws UnexpectedValueException
     */
    public function getDtoClass(): string;

    /**
     * Setter for used DTO class.
     */
    public function setDtoClass(string $dtoClass): self;

    /**
     * Getter method for current entity name.
     *
     * @throws Throwable
     */
    public function getEntityName(): string;

    /**
     * Gets a reference to the entity identified by the given type and
     * identifier without actually loading it, if the entity is not yet
     * loaded.
     *
     * @throws Throwable
     */
    public function getReference(string $id): ?object;

    /**
     * Getter method for all associations that current entity contains.
     *
     * @return array<int, string>
     *
     * @throws Throwable
     */
    public function getAssociations(): array;

    /**
     * Getter method DTO class with loaded entity data.
     *
     * @codeCoverageIgnore This is needed because variables are multiline
     *
     * @throws Throwable
     */
    public function getDtoForEntity(
        string $id,
        string $dtoClass,
        RestDtoInterface $dto,
        ?bool $patch = null
    ): RestDtoInterface;

    /**
     * Generic find method to return an array of items from database. Return
     * value is an array of specified repository entities.
     *
     * @codeCoverageIgnore This is needed because variables are multiline
     *
     * @param array<int|string, string|array<mixed>>|null $criteria
     * @param array<string, string>|null $orderBy
     * @param array<string, string>|null $search
     *
     * @return array<int, EntityInterface>
     *
     * @throws Throwable
     */
    public function find(
        ?array $criteria = null,
        ?array $orderBy = null,
        ?int $limit = null,
        ?int $offset = null,
        ?array $search = null
    ): array;

    /**
     * Generic findOne method to return single item from database. Return value
     * is single entity from specified repository.
     *
     * @psalm-return (
     *      $throwExceptionIfNotFound is true
     *      ? EntityInterface
     *      : EntityInterface|null
     *  )
     *
     * @throws Throwable
     */
    public function findOne(string $id, ?bool $throwExceptionIfNotFound = null): ?EntityInterface;

    /**
     * Generic findOneBy method to return single item from database by given
     * criteria. Return value is single entity from specified repository or
     * null if entity was not found.
     *
     * @codeCoverageIgnore This is needed because variables are multiline
     *
     * @param array<int|string, string|array<mixed>> $criteria
     * @param array<int, string>|null $orderBy
     *
     * @psalm-return (
     *      $throwExceptionIfNotFound is true
     *      ? EntityInterface
     *      : EntityInterface|null
     *  )
     *
     * @throws Throwable
     */
    public function findOneBy(
        array $criteria,
        ?array $orderBy = null,
        ?bool $throwExceptionIfNotFound = null
    ): ?EntityInterface;

    /**
     * Generic count method to return entity count for specified criteria and
     * search terms.
     *
     * @param array<int|string, string|array<mixed>>|null $criteria
     * @param array<string, string>|null $search
     *
     * @throws Throwable
     */
    public function count(?array $criteria = null, ?array $search = null): int;

    /**
     * Generic method to create new item (entity) to specified database
     * repository. Return value is created entity for specified repository.
     *
     * @throws Throwable
     */
    public function create(RestDtoInterface $dto, ?bool $flush = null, ?bool $skipValidation = null): EntityInterface;

    /**
     * Generic method to update specified entity with new data.
     *
     * @codeCoverageIgnore This is needed because variables are multiline
     *
     * @throws Throwable
     */
    public function update(
        string $id,
        RestDtoInterface $dto,
        ?bool $flush = null,
        ?bool $skipValidation = null
    ): EntityInterface;

    /**
     * Generic method to patch specified entity with new data.
     *
     * @codeCoverageIgnore This is needed because variables are multiline
     *
     * @throws Throwable
     */
    public function patch(
        string $id,
        RestDtoInterface $dto,
        ?bool $flush = null,
        ?bool $skipValidation = null
    ): EntityInterface;

    /**
     * Generic method to delete specified entity from database.
     *
     * @throws Throwable
     */
    public function delete(string $id, ?bool $flush = null): EntityInterface;

    /**
     * Generic ids method to return an array of id values from database. Return
     * value is an array of specified repository entity id values.
     *
     * @param array<int|string, string|array<mixed>>|null $criteria
     * @param array<string, string>|null $search
     *
     * @return array<int, string>
     */
    public function getIds(?array $criteria = null, ?array $search = null): array;

    /**
     * Generic method to save given entity to specified repository. Return
     * value is created entity.
     *
     * @throws Throwable
     */
    public function save(EntityInterface $entity, ?bool $flush = null, ?bool $skipValidation = null): EntityInterface;
}
