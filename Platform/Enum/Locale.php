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
 * /src/Enum/Locale.php
 *
 */

namespace Platform\Enum;


use Platform\Enum\Interfaces\DatabaseEnumInterface as DatabaseEnumInterfaceAlias;
use Platform\Enum\Traits\GetValues;

/**
 * Locale enum
 */
enum Locale: string implements DatabaseEnumInterfaceAlias
{
    use GetValues;

    case EN = 'en';
    case FI = 'fi';

    public static function getDefault(): self
    {
        return self::EN;
    }
}
