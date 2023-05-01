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
 * /tests/Integration/Helpers/src/LoggerAwareService.php
 *
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */

namespace App\Tests\Integration\Helpers\src;

use Platform\Helpers\LoggerAwareTrait;

/**
 * Class LoggerAwareService
 *
 * @package App\Tests\Integration\Helpers\src
 * @author TLe, Tarmo Leppänen <tarmo.leppanen@pinja.com>
 */
class LoggerAwareService
{
    use LoggerAwareTrait;

    public function __construct()
    {
        $this->logger = null;
    }
}
