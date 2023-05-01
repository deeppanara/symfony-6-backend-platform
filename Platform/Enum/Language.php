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
 * /src/Enum/Language.php
 *
 */

namespace Platform\Enum;

use Platform\Enum\Interfaces\DatabaseEnumInterface as DatabaseEnumInterfaceAlias;
use Platform\Enum\Traits\GetValues;

/**
 * Language enum
 */
enum Language: string implements DatabaseEnumInterfaceAlias
{
    use GetValues;

    case EN = 'en';
    case FI = 'fi';

    public static function getDefault(): self
    {
        return self::EN;
    }
}
