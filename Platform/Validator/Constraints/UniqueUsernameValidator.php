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
 * /src/Validator/Constraints/UniqueUsernameValidator.php
 *
 */

namespace Platform\Validator\Constraints;

use Doctrine\ORM\NonUniqueResultException;
use Platform\Entity\Interfaces\UserInterface;
use Platform\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


/**
 *
 */
class UniqueUsernameValidator extends ConstraintValidator
{
    public function __construct(
        private readonly UserRepository $repository,
    ) {
    }

    /**
     * {@inheritdoc}
     *
     * @throws NonUniqueResultException
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if ($value instanceof UserInterface
            && !$this->repository->isUsernameAvailable($value->getUsername(), $value->getId())
        ) {
            $this->context
                ->buildViolation(UniqueUsername::MESSAGE)
                ->setCode(UniqueUsername::IS_UNIQUE_USERNAME_ERROR)
                ->addViolation();
        }
    }
}
