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
 * /src/Form/Type/Traits/AddBasicFieldToForm.php
 *
 */

namespace Platform\Form\Type\Traits;

use Symfony\Component\Form\FormBuilderInterface;
use function call_user_func_array;

/**
 * Trait AddBasicFieldToForm
 */
trait AddBasicFieldToForm
{
    /**
     * @param array<int, array<int, mixed>> $fields
     */
    protected function addBasicFieldToForm(FormBuilderInterface $builder, array $fields): void
    {
        foreach ($fields as $params) {
            call_user_func_array($builder->add(...), $params);
        }
    }
}
