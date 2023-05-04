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
 * /src/Doctrine/DBAL/Types/EnumLocaleType.php
 *
 */

namespace Platform\Doctrine\DBAL\Types;

use BackedEnum;
use Platform\Enum\Interfaces\DatabaseEnumInterface;
use Platform\Enum\Locale;


/**
 *
 */
class EnumLocaleType extends RealEnumType
{
    protected static string $name = Types::ENUM_LOCALE;

    /**
     * @psalm-var class-string<DatabaseEnumInterface&BackedEnum>
     */
    protected static string $enum = Locale::class;
}
