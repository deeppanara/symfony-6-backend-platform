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
 * /src/Validator/Constraints/Timezone.php
 *
 */

namespace Platform\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/*
 *
 * Usage example;
 *  Platform\Validator\Constraints\Timezone()
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
class Timezone extends Constraint
{
    final public const INVALID_TIMEZONE = '1f8dd2a3-5b61-43ca-a6b2-af553f86ac17';
    final public const MESSAGE = 'This timezone "{{ timezone }}" is not valid.';

    /**
     * {@inheritdoc}
     *
     * @psalm-var array<string, string>
     */
    protected const ERROR_NAMES = [
        self::INVALID_TIMEZONE => 'INVALID_TIMEZONE',
    ];
}
