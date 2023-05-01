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
 * @date     01/05/23, 12:17 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Form/DataTransformer/UserGroupTransformer.php
 *
 */

namespace Platform\Form\DataTransformer;

use Platform\Entity\UserGroup;
use Platform\Resource\UserGroupResource;
use Stringable;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Throwable;
use function array_map;
use function is_array;
use function sprintf;

/*
 *
 * @psalm-suppress MissingTemplateParam
 */

/**
 *
 */
class UserGroupTransformer implements DataTransformerInterface
{
    public function __construct(
        private readonly UserGroupResource $resource
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * Transforms an array of objects (UserGroup) to an array of strings
     * (UserGroup id).
     *
     * @psalm-param array<int, string|UserGroup>|mixed $value
     * @psalm-return array<array-key, string>
     */
    public function transform(mixed $value): array
    {
        $callback = static fn (UserGroup | Stringable $userGroup): string =>
            $userGroup instanceof UserGroup ? $userGroup->getId() : (string)$userGroup;

        return is_array($value) ? array_map($callback, $value) : [];
    }

    /**
     * {@inheritdoc}
     *
     * Transforms an array of strings (UserGroup id) to an array of objects
     * (UserGroup).
     *
     * @psalm-param array<int, string>|mixed $value
     * @psalm-return array<array-key, UserGroup>|null
     *
     * @throws Throwable
     */
    public function reverseTransform(mixed $value): ?array
    {
        return is_array($value)
            ? array_map(
                fn (string $groupId): UserGroup => $this->resource->findOne($groupId, false) ??
                    throw new TransformationFailedException(
                        sprintf('User group with id "%s" does not exist!', $groupId),
                    ),
                $value,
            )
            : null;
    }
}
