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
 * /src/Form/DataTransformer/RoleTransformer.php
 *
 */

namespace Platform\Form\DataTransformer;

use Platform\Entity\Role;
use Platform\Resource\RoleResource;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Throwable;
use function is_string;
use function sprintf;

/*
 *
 * @psalm-suppress MissingTemplateParam
 */

/**
 *
 */
class RoleTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly RoleResource $resource,
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * Transforms an object (Role) to a string (Role id).
     *
     * @psalm-param Role|mixed $value
     */
    public function transform(mixed $value): string
    {
        return $value instanceof Role ? $value->getId() : '';
    }

    /**
     * {@inheritdoc}
     *
     * Transforms a string (Role id) to an object (Role).
     *
     * @phpstan-param mixed $value
     *
     * @throws Throwable
     */
    public function reverseTransform(mixed $value): ?Role
    {
        return is_string($value)
            ? $this->resource->findOne($value, false) ?? throw new TransformationFailedException(
                sprintf(
                    'Role with name "%s" does not exist!',
                    $value
                ),
            )
            : null;
    }
}
