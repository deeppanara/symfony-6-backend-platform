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
 * /src/Validator/Constraints/Language.php
 *
 */

namespace Platform\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/*
 *
 * Usage example;
 *  Platform\Validator\Constraints\Language()
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
class Language extends Constraint
{
    final public const INVALID_LANGUAGE = '08bd61cf-ba27-45a3-9916-78c39253833a';
    final public const MESSAGE = 'This language "{{ language }}" is not valid.';

    /**
     * {@inheritdoc}
     *
     * @psalm-var array<string, string>
     */
    protected const ERROR_NAMES = [
        self::INVALID_LANGUAGE => 'INVALID_LANGUAGE',
    ];
}
