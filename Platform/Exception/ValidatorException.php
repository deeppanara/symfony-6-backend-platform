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
 * /src/Exception/ValidatorException.php
 *
 */

namespace Platform\Exception;

use JsonException;
use Platform\Exception\interfaces\ClientErrorInterface;
use Platform\Exception\models\ValidatorError;
use Platform\Utils\JSON;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Exception\ValidatorException as BaseValidatorException;
use function array_map;
use function iterator_to_array;


/**
 *
 */
class ValidatorException extends BaseValidatorException implements ClientErrorInterface
{
    /**
     * @throws JsonException
     */
    public function __construct(string $target, ConstraintViolationListInterface $errors)
    {
        parent::__construct(
            JSON::encode(
                array_map(
                    static fn (ConstraintViolationInterface $error): ValidatorError =>
                        new ValidatorError($error, $target),
                    iterator_to_array($errors),
                ),
            ),
        );
    }

    public function getStatusCode(): int
    {
        return 400;
    }
}
