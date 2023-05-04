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
 * /src/Validator/Constraints/Locale.php
 *
 */

namespace Platform\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/*
 *
 * Usage example;
 *  Platform\Validator\Constraints\Locale()
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
class Locale extends Constraint
{
    final public const INVALID_LOCALE = '44e3862f-2d38-46d4-b1ae-632990814af6';
    final public const MESSAGE = 'This locale "{{ locale }}" is not valid.';

    /**
     * {@inheritdoc}
     *
     * @psalm-var array<string, string>
     */
    protected const ERROR_NAMES = [
        self::INVALID_LOCALE => 'INVALID_LOCALE',
    ];
}
