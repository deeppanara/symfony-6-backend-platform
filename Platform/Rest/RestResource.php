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
 * /src/Rest/RestResource.php
 *
 */

namespace Platform\Rest;

use Platform\DTO\RestDtoInterface;
use Platform\Repository\Interfaces\BaseRepositoryInterface;
use Platform\Rest\Interfaces\RestResourceInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Contracts\Service\Attribute\Required;
use UnexpectedValueException;
use function array_keys;
use function sprintf;


/**
 *
 */
abstract class RestResource implements RestResourceInterface
{
    use \Platform\Rest\Traits\RestResourceBaseMethods;

    private ValidatorInterface $validator;
    private string $dtoClass = '';

    public function __construct(
        protected readonly BaseRepositoryInterface $repository,
    ) {
    }

    public function getSerializerContext(): array
    {
        return [];
    }

    public function getRepository(): BaseRepositoryInterface
    {
        return $this->repository;
    }

    public function getValidator(): ValidatorInterface
    {
        return $this->validator;
    }

    #[Required]
    public function setValidator(ValidatorInterface $validator): self
    {
        $this->validator = $validator;

        return $this;
    }

    public function getDtoClass(): string
    {
        if ($this->dtoClass === '') {
            $message = sprintf(
                'DTO class not specified for \'%s\' resource',
                static::class
            );

            throw new UnexpectedValueException($message);
        }

        return $this->dtoClass;
    }

    public function setDtoClass(string $dtoClass): RestResourceInterface
    {
        $this->dtoClass = $dtoClass;

        return $this;
    }

    public function getEntityName(): string
    {
        return $this->getRepository()->getEntityName();
    }

    public function getReference(string $id): ?object
    {
        return $this->getRepository()->getReference($id);
    }

    public function getAssociations(): array
    {
        return array_keys($this->getRepository()->getAssociations());
    }

    public function getDtoForEntity(
        string $id,
        string $dtoClass,
        RestDtoInterface $dto,
        ?bool $patch = null
    ): RestDtoInterface {
        $patch ??= false;

        // Fetch entity
        $entity = $this->getEntity($id);

        /**
         * Create new instance of DTO and load entity to that.
         *
         * @var RestDtoInterface $restDto
         * @var class-string<RestDtoInterface> $dtoClass
         */
        $restDto = (new $dtoClass())
            ->setId($id);

        if ($patch === true) {
            $restDto->load($entity);
        }

        $restDto->patch($dto);

        return $restDto;
    }
}
