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
 * @date     01/05/23, 11:59 am
 * *************************************************************************
 */

declare(strict_types = 1);
/**
 * /src/Enum/Traits/GetValues.php
 *
 */

namespace Platform\Enum\Traits;

use function array_column;

/**
 * Trait GetValues
 */
trait GetValues
{
    /**
     * @return array<int, string>
     */
    public static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
