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
 * /src/Validator/Constraints/UniqueUsername.php
 *
 */

namespace Platform\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/*
 *
 * Usage example;
 *  Platform\Validator\Constraints\UniqueUsername()
 *
 * Just add that to your class as an annotation and you're good to go.
 *
 * @Annotation
 * @Target({"CLASS"})
 */

/**
 *
 */
#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class UniqueUsername extends Constraint
{
    final public const IS_UNIQUE_USERNAME_ERROR = 'ea62740a-4d9b-4a25-9a56-46fb4c3d5fea';
    final public const MESSAGE = 'This username is already taken.';

    /**
     * {@inheritdoc}
     *
     * @psalm-var array<string, string>
     */
    protected const ERROR_NAMES = [
        self::IS_UNIQUE_USERNAME_ERROR => 'IS_UNIQUE_USERNAME_ERROR',
    ];

    public function getTargets(): string
    {
        $output = null;

        if (parent::getTargets() !== self::CLASS_CONSTRAINT) {
            $output = self::CLASS_CONSTRAINT;
        }

        return $output ?? self::CLASS_CONSTRAINT;
    }
}
