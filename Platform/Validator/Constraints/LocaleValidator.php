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
 * @date     01/05/23, 12:19 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Validator/Constraints/LocaleValidator.php
 *
 */

namespace Platform\Validator\Constraints;

use Platform\Service\Localization;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use function in_array;


/**
 *
 */
class LocaleValidator extends ConstraintValidator
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
        if (in_array($value, $this->localization->getLocales(), true) !== true) {
            $this->context
                ->buildViolation(Locale::MESSAGE)
                ->setParameter('{{ locale }}', (string)$value)
                ->setCode(Locale::INVALID_LOCALE)
                ->addViolation();
        }
    }
}
