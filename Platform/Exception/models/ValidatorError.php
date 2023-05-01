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
 * @date     01/05/23, 12:17 pm
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Exception/models/ValidatorError.php
 *
 */

namespace Platform\Exception\models;

use Stringable;
use Symfony\Component\Validator\ConstraintViolationInterface;
use function str_replace;


/**
 *
 */
class ValidatorError
{
    public string | Stringable $message;
    public string $propertyPath;
    public string $target;
    public string | null $code;

    public function __construct(ConstraintViolationInterface $error, string $target)
    {
        $this->message = $error->getMessage();
        $this->propertyPath = $error->getPropertyPath();
        $this->target = str_replace('\\', '.', $target);
        $this->code = $error->getCode();
    }
}
