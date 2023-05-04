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
 * /src/Validator/Constraints/UniqueEmail.php
 *
 */

namespace Platform\Validator\Constraints;

use Attribute;
use Symfony\Component\Validator\Constraint;

/*
 *
 * Usage example;
 *  Platform\Validator\Constraints\UniqueEmail()
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
class UniqueEmail extends Constraint
{
    final public const IS_UNIQUE_EMAIL_ERROR = 'd487278d-8b13-4da0-b4cc-f862e6e99af6';
    final public const MESSAGE = 'This email is already taken.';

    /**
     * {@inheritdoc}
     *
     * @psalm-var array<string, string>
     */
    protected const ERROR_NAMES = [
        self::IS_UNIQUE_EMAIL_ERROR => 'IS_UNIQUE_EMAIL_ERROR',
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
