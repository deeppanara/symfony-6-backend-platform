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
 * /src/DTO/RestDtoInterface.php
 *
 */

namespace Platform\DTO;

use Platform\Entity\Interfaces\EntityInterface;
use Throwable;

/**
 * Interface RestDtoInterface
 */
interface RestDtoInterface
{
    public function setId(string $id): self;

    /**
     * Getter method for visited setters. This is needed for dto patching.
     *
     * @return array<int, string>
     */
    public function getVisited(): array;

    /**
     * Setter for visited data. This is needed for dto patching.
     */
    public function setVisited(string $property): self;

    /**
     * Method to load DTO data from specified entity.
     */
    public function load(EntityInterface $entity): self;

    /**
     * Method to update specified entity with DTO data.
     */
    public function update(EntityInterface $entity): EntityInterface;

    /**
     * Method to patch current dto with another one.
     *
     * @throws Throwable
     */
    public function patch(self $dto): self;
}
