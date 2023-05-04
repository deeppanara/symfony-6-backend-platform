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
 * /src/Validator/Constraints/TimezoneValidator.php
 *
 */

namespace Platform\Validator\Constraints;

use Platform\Service\Localization;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use function array_column;
use function in_array;
use function is_string;


/**
 *
 */
class TimezoneValidator extends ConstraintValidator
{
    public function __construct(
        private readonly Localization $localization,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (is_string($value)
            && !in_array($value, array_column($this->localization->getTimezones(), 'identifier'), true)
        ) {
            $this->context
                ->buildViolation(Timezone::MESSAGE)
                ->setParameter('{{ timezone }}', $value)
                ->setCode(Timezone::INVALID_TIMEZONE)
                ->addViolation();
        }
    }
}
