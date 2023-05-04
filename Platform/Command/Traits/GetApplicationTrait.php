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
 * /src/Command/Traits/GetApplicationTrait.php
 *
 */

namespace Platform\Command\Traits;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Exception\RuntimeException;

/**
 * Trait GetApplicationTrait
 */
trait GetApplicationTrait
{
    /**
     * @throws RuntimeException
     */
    public function getApplication(): Application
    {
        return parent::getApplication()
            ?? throw new RuntimeException('Cannot determine application for console command to use.');
    }
}
