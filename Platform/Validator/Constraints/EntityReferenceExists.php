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
 * @date     01/05/23, 12:36 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Validator/Constraints/EntityReferenceExists.php
 *
 */

namespace Platform\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/*
 *
 * Usage example;
 *  #[Platform\Validator\Constraints\EntityReferenceExists(SomeEntityClass::class)]
 *
 * Just add that to your property as an annotation and you're good to go.
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */

/**
 *
 */
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class EntityReferenceExists extends Constraint
{
    final public const ENTITY_REFERENCE_EXISTS_ERROR = '64888b5e-bded-449b-82ed-0cc1f73df14d';
    final public const MESSAGE_SINGLE = 'Invalid id value "{{ id }}" given for entity "{{ entity }}".';
    final public const MESSAGE_MULTIPLE = 'Invalid id values "{{ id }}" given for entity "{{ entity }}".';

    /**
     * {@inheritdoc}
     *
     * @psalm-var array<string, string>
     */
    protected const ERROR_NAMES = [
        self::ENTITY_REFERENCE_EXISTS_ERROR => 'ENTITY_REFERENCE_EXISTS_ERROR',
    ];

    public string $entityClass = '';

    /**
     * EntityReferenceExists constructor.
     *
     * @inheritDoc
     *
     * @param array<string, string> $options
     * @param array<array-key, string> $groups
     */
    public function __construct(
        ?string $entityClass = null,
        array $options = [],
        array $groups = [],
        mixed $payload = null,
    ) {
        $this->entityClass = $entityClass ?? $options['entityClass'] ?? '';

        parent::__construct($options, $groups, $payload);
    }
}
